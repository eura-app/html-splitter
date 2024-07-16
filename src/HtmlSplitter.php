<?php

namespace David\HtmlSplitter;

class HtmlSplitter
{
    public function fromHtml(string $html, int $max_characters_per_row = 99999): array
    {
        return $this->splitHtmlByLength($html, $max_characters_per_row);
    }

    private function splitHtmlByLength($html, $length)
    {
        $dom = new \DOMDocument();

        // Foutmeldingen inschakelen tijdens het laden van HTML
        libxml_use_internal_errors(true);
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Controleer of er een body-element is
        $body = $dom->getElementsByTagName('body')->item(0);
        $nodes = $body ? $body->childNodes : $dom->childNodes;

        $chunks = [];
        $currentChunk = '';
        $currentLength = 0;

        foreach ($nodes as $child) {
            $this->processNode($child, $chunks, $currentChunk, $currentLength, $length);
        }

        if ($currentLength > 0) {
            $chunks[] = $currentChunk;
        }

        return $chunks;
    }

    private function processNode($node, &$chunks, &$currentChunk, &$currentLength, $maxLength)
    {
        if ($node->nodeType == XML_TEXT_NODE) {
            $text = $node->textContent;
            for ($i = 0; $i < strlen($text); $i++) {
                $currentChunk .= $text[$i];
                $currentLength++;
                if ($currentLength == $maxLength) {
                    $chunks[] = $currentChunk;
                    $currentChunk = '';
                    $currentLength = 0;
                }
            }
        } elseif ($node->nodeType == XML_ELEMENT_NODE) {
            $html = $node->ownerDocument->saveHTML($node);
            $currentChunk .= $html;
            $currentLength += strlen($node->textContent);

            if ($currentLength >= $maxLength) {
                $chunks[] = $currentChunk;
                $currentChunk = '';
                $currentLength = 0;
            }
        }

        foreach ($node->childNodes as $child) {
            $this->processNode($child, $chunks, $currentChunk, $currentLength, $maxLength);
        }
    }
}

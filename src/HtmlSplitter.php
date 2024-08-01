<?php

namespace David\HtmlSplitter;

class HtmlSplitter
{
    public function generate(?string $text, int $amount_rows = 40, int $characters_per_row = 50): array
    {
        if (! $text) {
            return [];
        }
        $text = str_replace('</div>', '', $text);
        $array = explode('<div>', $text);

        $rows = $this->splitSentencesByMaxCharacters($array, $characters_per_row);

        $new_rows = [];
        foreach ($rows as $row) {
            $value = trim($row);
            if (! empty($value)) {
                $new_rows[] = $value;
            }
        }
        $rows = $new_rows;

        $blocks = $this->mergeRowsByMaxAmountRows($rows, $amount_rows);
        if (! $blocks) {
            return [];
        }
        //remove the first <br> tag
        $blocks[0] = (preg_replace('/^(?:<br\s*\/?>\s*)+/', '', $blocks[0]));

        // array_filter to remove empty blocks
        return array_values(array_filter($blocks));
    }

    private function splitSentencesByMaxCharacters($rows, $characters_per_row): array
    {
        $new_rows = [];
        foreach ($rows as $row) {

            $parts = preg_split('/(<ul>.*<\/ul>)/sU', $row, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

            foreach ($parts as $part) {
                if (! str_starts_with($part, '<ul>')) {
                    $text = wordwrap($part, $characters_per_row);
                    $lines = explode("\n", $text);
                } else {
                    $lis = preg_split('/(<li>.*<\/li>)/sU', $part, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                    //remove the <ul> opening and closing (we will add them later )
                    array_pop($lis);
                    array_shift($lis);

                    $lines = $lis;
                }

                $new_rows = array_merge($new_rows, $lines);

            }
        }

        return $new_rows;
    }

    private function mergeRowsByMaxAmountRows(array $rows, int $amount_rows): array
    {
        $blocks = [];

        while (count($rows) > 0) {
            $latest_br = 0;
            if (count($rows) <= $amount_rows) {
                // Add the final data to the end of the array
                $blocks[]['text'] = str_replace('<br>', '', implode('<br/>', $rows));
                break;
            }
            foreach ($rows as $index => $row) {
                if ($row === '<br>') {
                    //Plus 1 so we also add the <br> to the block
                    $latest_br = $index + 1;
                }
                if ($index === $amount_rows) {
                    break;
                }
            }
            if ($latest_br === 0) {
                $latest_br = $amount_rows;
            }
            $current_block = array_splice($rows, 0, $latest_br);

            // Remove the last <br>
            if ($current_block[count($current_block) - 1] === '<br>') {
                array_pop($current_block);
            }

            $text = '';
            foreach ($current_block as $index => $current) {
                if ($index === count($current_block) - 1) {
                    $text .= $current;

                    continue;
                }
                if (str_ends_with($current, '</li>')) {
                    $text .= $current;

                    continue;
                }
                $text .= $current.'<br/>';
            }
            $blocks[]['text'] = str_replace('<br>', '', $text);
        }
        foreach ($blocks as $key => $block) {
            $blocks[$key]['text'] = $this->addUlTags($block['text']);
        }

        return $blocks;
    }

    public function addUlTags($text)
    {
        // Voeg <ul> toe voor de eerste <li>
        $text = preg_replace('/(<li>)/', '<ul>$1', $text, 1);
        // Voeg </ul> toe na de laatste </li>
        $text = preg_replace('/(<\/li>)(?!.*<\/li>)/', '$1</ul>', $text);

        return $text;
    }
}

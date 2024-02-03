<?php declare(strict_types=1);

namespace App\Services\LineCorrector;

class FirstLastMapReplacer implements LineCorrectorInterface
{
    private string $line;

    private array $rule;

    private array $indexReplacements;

    private int $reduction = 0;

    public function correct(string $line, array $rule): string
    {
        $this->line = $line;
        $this->rule = $rule;
        
        $this->buildAndSortIndexReplacementMap();

        if (empty($this->indexReplacements)) {
            return $this->line;
        }
        
        $indexes = array_keys($this->indexReplacements);

        $this->replaceFirstInLine(array_shift($indexes));

        if (count($this->indexReplacements) === 1) {
            return $this->line;
        }
        $this->replaceLastInLine(end($indexes));
        
        return $this->line;
    }

    private function buildAndSortIndexReplacementMap(): void
    {
        $this->indexReplacements = [];
        foreach($this->rule as $search => $replace) {
            $offset = 0;
            while (($index = strpos($this->line, $search, $offset)) !== false) {
                $offset += strlen($search);
                $this->indexReplacements[$index] = $search;
            }
        }
        ksort($this->indexReplacements);
    }

    private function replaceFirstInLine(int $index): void
    {
        $sub = $this->indexReplacements[$index];
        $this->reduction = strlen($sub);
        $this->line = substr_replace($this->line, (string)$this->rule[$sub], $index, $this->reduction);
    }

    private function replaceLastInLine(int $index): void
    {
        $sub = $this->indexReplacements[$index];
        $offset = strrpos($this->line, $sub);
        if ($offset === false) {
            $len = strlen($sub) - 1;
            $offset = $index - $this->reduction + 2;
        } else {
            $len = strlen($sub);
        }
        $this->line = substr_replace($this->line, (string)$this->rule[$sub], $offset, $len);
    }
}

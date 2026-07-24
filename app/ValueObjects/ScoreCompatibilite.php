<?php

namespace App\ValueObjects;

class ScoreCompatibilite
{
    public function __construct(
        public readonly int $score,
        public readonly string $justification,
        public readonly string $horaire_suggere,
        public readonly array $points_forts = [],
        public readonly array $points_faibles = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            score: (int) ($data['score'] ?? 0),
            justification: $data['justification'] ?? '',
            horaire_suggere: $data['horaire_suggere'] ?? '',
            points_forts: $data['points_forts'] ?? [],
            points_faibles: $data['points_faibles'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'score'          => $this->score,
            'justification'  => $this->justification,
            'horaire_suggere'=> $this->horaire_suggere,
            'points_forts'   => $this->points_forts,
            'points_faibles' => $this->points_faibles,
        ];
    }

    public function couleurScore(): string
    {
        return match (true) {
            $this->score >= 80 => 'emerald',
            $this->score >= 60 => 'amber',
            $this->score >= 40 => 'orange',
            default            => 'red',
        };
    }

    public function libelleCouleur(): string
    {
        return match (true) {
            $this->score >= 80 => 'Excellent',
            $this->score >= 60 => 'Bon',
            $this->score >= 40 => 'Moyen',
            default            => 'Faible',
        };
    }
}

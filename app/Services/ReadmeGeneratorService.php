<?php

namespace App\Services;

class ReadmeGeneratorService
{
    public function generate(string $description, ?string $projectName = null, string $template = 'default'): string
    {
        $projectTitle = $projectName ?: 'Project';

        $overview = $this->buildOverview($projectTitle, $description);
        $features = $this->extractFeatures($description);
        $installation = $this->suggestInstallation();
        $usage = $this->suggestUsage($projectTitle);
        $tech = $this->suggestTechStack($description);

        $readme = "# {$projectTitle}\n\n";
        $readme .= $overview . "\n\n";
        $readme .= "## Key Features\n\n";
        foreach ($features as $f) {
            $readme .= "- {$f}\n";
        }
        $readme .= "\n## Installation\n\n" . $installation . "\n\n";
        $readme .= "## Usage\n\n" . $usage . "\n\n";
        $readme .= "## Tech Stack\n\n" . $tech . "\n";

        return trim($readme);
    }

    protected function buildOverview(string $title, string $description): string
    {
        return $description;
    }

    protected function extractFeatures(string $description): array
    {
        // naive extraction: split by sentences and take up to 6 phrases that look like features
        $sentences = preg_split('/(?<=[.!?])\s+/', trim($description));
        $features = [];
        foreach ($sentences as $s) {
            $s = trim($s, " .,!?\t\n\r");
            if ($s === '') {
                continue;
            }
            $features[] = ucfirst($s);
            if (count($features) >= 6) {
                break;
            }
        }

        if (empty($features)) {
            $features[] = 'Clean, well-structured documentation';
        }

        return $features;
    }

    protected function suggestInstallation(): string
    {
        return "1. Clone the repository\n2. Install dependencies (e.g. composer install / npm install)\n3. Configure environment variables\n4. Run database migrations\n\n```bash\n# Example\ncomposer install\ncp .env.example .env\nphp artisan key:generate\nphp artisan migrate\n```";
    }

    protected function suggestUsage(string $title): string
    {
        return "After installing, start the application and follow these steps to use {$title}:\n\n```bash\nphp artisan serve\n# or\nnpm run dev\n```\n\nThen open your browser to http://localhost:8000 and follow the UI to create and manage content.";
    }

    protected function suggestTechStack(string $description): string
    {
        $tech = [];

        $lower = strtolower($description);
        if (strpos($lower, 'laravel') !== false) {
            $tech[] = 'PHP (Laravel)';
        }
        if (strpos($lower, 'react') !== false) {
            $tech[] = 'JavaScript (React)';
        }
        if (strpos($lower, 'vue') !== false) {
            $tech[] = 'JavaScript (Vue.js)';
        }
        if (strpos($lower, 'docker') !== false) {
            $tech[] = 'Docker';
        }
        if (strpos($lower, 'graphql') !== false) {
            $tech[] = 'GraphQL';
        }

        if (empty($tech)) {
            $tech = ['PHP', 'Laravel', 'MySQL/MariaDB', 'Redis (optional)', 'Inertia/Blade or SPA frontend'];
        }

        return '- ' . implode("\n- ", $tech);
    }
}

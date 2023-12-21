<?php declare(strict_types=1);
/*
 * This file is part of saviorenato/version.
 *
 * (c) Savio Pereira <saviorenato@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SavioPereira;

use function end;
use function explode;
use function fclose;
use function is_dir;
use function is_resource;
use function proc_close;
use function proc_open;
use function stream_get_contents;
use function substr_count;
use function trim;

final class Version
{
    private readonly string $version;

    public function __construct(private string $release, private string $path)
    {
        
    }

    public function version(): string
    {
        return $this->version = $this->generate($this->release);
    }

    public function versionGit(): string
    {
        return $this->version = $this->generateGit($this->release, $this->path);
    }

    private function generate(string $release): string
    {
        $version = $release . '-dev';

        if (substr_count($release, '.') + 1 === 3) {
            $version = $release;
        }

        return $version;
    }

    private function generateGit(string $release, string $path): string
    {
        if (substr_count($release, '.') + 1 === 3) {
            $version = $release;
        } else {
            $version = $release . '-dev';
        }

        $git = $this->getGitInformation($path);

        if (!$git) {
            return $version;
        }

        if (substr_count($release, '.') + 1 === 3) {
            return $git;
        }

        $git = explode('-', $git);

        return $release . '-' . end($git);
    }

    private function getGitInformation(string $path): bool|string
    {
        if (!is_dir($path . DIRECTORY_SEPARATOR . '.git')) {
            return false;
        }

        $process = proc_open(
            'git describe --tags',
            [
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
            $path
        );

        if (!is_resource($process)) {
            return false;
        }

        $result = trim(stream_get_contents($pipes[1]));

        fclose($pipes[1]);
        fclose($pipes[2]);

        $returnCode = proc_close($process);

        if ($returnCode !== 0) {
            return false;
        }

        return $result;
    }
}

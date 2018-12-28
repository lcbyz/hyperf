<?php
declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://hyperf.org
 * @document https://wiki.hyperf.org
 * @contact  group@hyperf.org
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace Hyperf\Devtool\Command;

use Hyperf\Config\ProviderConfig;
use Hyperf\Di\Annotation\Scanner;
use Hyperf\Framework\Server;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ProxyCreateCommand extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Scanner
     */
    private $scanner;

    public function __construct(ContainerInterface $container, Scanner $scanner)
    {
        parent::__construct('proxy:create');
        $this->container = $container;
        $this->scanner = $scanner;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = BASE_PATH . '/config/autoload/annotations.php';
        if (!file_exists($file)) {
            $output->writeln("<error>ERROR</error> Annotations config path[$file] is not exists.");
            exit(0);
        }

        $runtime = BASE_PATH . '/runtime/container/proxy/';
        if(is_dir($runtime)){
            $this->clearRuntime($runtime);
        }

        $annotations = include $file;
        $configFromProviders = ProviderConfig::load();
        $scanDirs = $configFromProviders['scan']['paths'];
        $scanDirs = array_merge($scanDirs, $annotations['scan']['paths'] ?? []);

        $classCollection = $this->scanner->scan($scanDirs);

        foreach ($classCollection as $item) {
            try {
                $this->container->get($item);
            } catch (\Throwable $ex) {
                // Entry cannot be resoleved.
            }
        }

        $output->writeln('<info>Proxy class create success.</info>');
    }

    protected function clearRuntime($paths)
    {
        $finder = new Finder();
        $finder->files()->in($paths)->name('*.php');

        /** @var SplFileInfo $file */
        foreach ($finder as $file){
            $path = $file->getRealPath();
            @unlink($path);
        }
    }
}

<?php

use Spatie\String\Str;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

require_once __DIR__.'/../vendor/autoload.php';

$application = new Application();

$application->addCommands([
    new class extends Command
    {
        /**
         * {@inheritdoc}
         */
        protected static $defaultName = 'main';

        /**
         * {@inheritdoc}
         */
        protected function configure()
        {
            $this->addArgument('title', InputArgument::OPTIONAL);
            $this->addArgument('excerpt', InputArgument::OPTIONAL);
            $this->addArgument('tags', InputArgument::IS_ARRAY);
        }

        /**
         * {@inheritdoc}
         */
        protected function interact(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            if (!$input->getArgument('title')) {
                $input->setArgument('title', $io->askQuestion(
                    new Question('The post title')
                ));
            }

            if (!$input->getArgument('excerpt')) {
                $input->setArgument('excerpt', $io->askQuestion(
                    new Question('The post excerpt')
                ));
            }

            $io->writeln("\nEnter any tags for the post.\nPress <info>enter</info> to <info>continue</info>.\n");
            $tags = [];

            while (true) {
                $tag = $io->askQuestion(
                    new Question('Enter a tag')
                );

                if (empty($tag)) {
                    break;
                }

                $tags[] = $tag;
            }

            $input->setArgument('tags', $tags);
        }

        /**
         * {@inheritdoc}
         */
        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $title = $input->getArgument('title');

            if (!$title) {
                throw new \RuntimeException('No title');
            }

            $excerpt = $input->getArgument('excerpt') ?? $title;
            $slug = (new Str($title))->slugify();
            $tags = '[' .  implode(', ', $input->getArgument('tags')) . ']';
            $date = (new \DateTime())->format('Y-m-d');

            $contents = $this->generateFileFromStub(
                $title,
                $excerpt,
                $tags,
                $date
            );

            $this->writeFile($slug, $contents);
            $this->createImagesDirectory($slug);

            $io->listing([
                "{$slug}.md"
            ]);
        }

        private function generateFileFromStub(...$args): string
        {
            [$title, $excerpt, $tags, $date] = $args;

            return <<<EOF
---
title: $title
excerpt: $excerpt
date: $date
tags: $tags
draft: true
---

TODO.
EOF;
        }

        private function writeFile(string $slug, string $contents): void
        {
            file_put_contents("source/_posts/{$slug}.md", $contents);
        }

        private function createImagesDirectory(string $slug): void
        {
            mkdir("source/images/{$slug}");
        }
    }
]);

$application->setDefaultCommand('main');
$application->run();

<?php
/* Load env file */
if (file_exists(__DIR__."/../../.env")) {
	$repository = Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
    ->addAdapter(Dotenv\Repository\Adapter\EnvConstAdapter::class)
    ->addWriter(Dotenv\Repository\Adapter\PutenvAdapter::class)
    ->immutable()
    ->make();

	$dotenv = Dotenv\Dotenv::create($repository, __DIR__."/../../");
	$dotenv->load();
}
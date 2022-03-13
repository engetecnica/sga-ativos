<?php

function get_combustiveis(object $configuracao) {
    return  [
        (object) [
            "nome" => "Etanol/Alcool",
            "slug" => "etanol",
            "unidade" => "litro",
            "valor_medio" => $configuracao->valor_medio_etanol,
        ],
        (object) [
            "nome" => "Gasolina",
            "slug" => "gasolina",
            "unidade" => "litro",
            "valor_medio" => $configuracao->valor_medio_gasolina,
        ],
        (object) [
            "nome" => "Diesel",
            "slug" => "diesel",
            "unidade" => "litro",
            "valor_medio" => $configuracao->valor_medio_diesel,
        ],
        (object) [
            "nome" => "GNV",
            "slug" => "gnv",
            "unidade" => "metro_cubico",
            "valor_medio" => $configuracao->valor_medio_gnv
        ]
    ];
}
<?php

function getMaquinasModelos(){
    return [
        [ "nome" => "Escavadeira", "codigo" => 1],
        [ "nome" => "Retroescavadeira", "codigo" => 2],
        [ "nome" => "Pá Carregadeira", "codigo" => 3],
        [ "nome" => "Empilhadeira", "codigo" => 4],
        [ "nome" => "Rolo Compactador Liso", "codigo" => 5],
        [ "nome" => "Rolo Compactador Liso (Pé de Caneiro)", "codigo" => 6],
        [ "nome" => "Rolo Pnemático", "codigo" => 7],
        [ "nome" => "Mini Escavadeira ", "codigo" => 8],
        [ "nome" => "Mini Carregadeira", "codigo" => 9],
        [ "nome" => "Munck", "codigo" => 12],
        [ "nome" => "Perfuratriz", "codigo" => 13],
        [ "nome" => "Trator Esteira", "codigo" => 10],
        [ "nome" => "Outro", "codigo" => 11],
    ];
}

function getMaquinasMarcas(){
    return [
        ["nome" => "Case", "codigo" => 1],
        ["nome" => "CAT | Caterpillar", "codigo" => 2],
        ["nome" => "Jhon Deere", "codigo" => 3],
        ["nome" => "Massey Ferguson", "codigo" => 4],
        ["nome" => "New Holland", "codigo" => 5],
        ["nome" => "Valtra", "codigo" => 6],
        ["nome" => "Bobcat", "codigo" => 7],
        ["nome" => "JCB", "codigo" => 8],
        ["nome" => "Outra", "codigo" => 9],
        ["nome" => "TKA", "codigo" => 10],
        ["nome" => "Argus", "codigo" => 11],
    ];
}

function getTipos($pt = false){
    if($pt) {
        return [
            'todos' => 'Todos',
            'carro' => 'Carro', 
            'moto' => 'Moto', 
            'caminhao' => 'Caminhão',
            'maquina' => 'Máquina'
        ];
    }

    return [
		"todos" => 'all',
		"carro" => "cars",
		"moto" => "motorcycles",
		"caminhao" => "trucks",
		"maquina" => "machine"
	];
}
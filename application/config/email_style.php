<?php
$colors = [
    "default" => "#fd9e0f",
    "black" => "#000",
    "black2" => "#002",
    "black3" => "#003",
    "black_shadow" => "rgba(0,0,0, .1)",
    "white" => "#FFF",
    "white2" => "#FFE",
];

return [
    //top
    "body"=> "padding: 0px; margin:0; font-family: sans-serif; font-size: 18px;",
    "body > div" => "padding: 40px; background: #eee; text-align: center;",
    "body > div > h1" => "color: {$colors['default']}; font-size: 35px; margin-bottom: 0;",
    "body > div > h2" => "color: {$colors['black3']}; font-size: 25px; margin-top: 0;",
    "body > div > img" => "width: 40vh;",

    //content
    "content" => "padding: 40px; color: #002;",
    "strong" => "font-size: 1.3rem;",
    "strong > b" => "color: {$colors['default']};",
    "p" => "font-size: 1rem;",
    "p > b" => "color: {$colors['default']};",
    "btn" => "
        background: {$colors['default']}; 
        color: {$colors['white']}; 
        font-weight: 400; font-size: 15px; 
        padding: 10px 15px;
        text-decoration:none; 
        border-radius: 5px;
        margin: 10px; 
        cursor: pointer;
    ",
    "table" => "border: none; box-shadow: 1px 1px 10px {$colors['black_shadow']}; border-radius: 8px; margin: 0 auto;",
    "thead" => "background-color: {$colors['black3']}; color: {$colors['white']}; font-size: 16px; text-align: center; font-weight: bold;",
    "tr_td_th" => "padding: 10px;",
    "first_th" => "border-radius: 5px 0 0 0;",
    "last_th" => "border-radius: 0 5px 0 0;",
    "tr" => "font-size: 12px; text-align: center; padding: 10px;",
    "tr2" => "background: {$colors['white2']};",
    "title" => "color: {$colors['black2']}; text-align: center;",

    //footer
    "footer" => "padding: 40px; color: #002; font-size: 12px;",
    "footer_text" => "<b>ENGETÉCNICA ENGENHARIA E CONSTRUÇÃO LTDA</b>, Rua João Bettega, n.1160, Portão, Curitiba-PR | Fone: (41) 4040-4676"
];
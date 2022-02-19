<?php
$colors = [
    "default" => "#fd9e0f",
    "black" => "#000",
    "black2" => "#002",
    "black3" => "#333",
    "black_shadow" => "rgba(0,0,0, .1)",
    "white" => "#FFF",
    "white2" => "#EEE",
];

return [
    //top
    "layout-img-top" => "
        margin-left: 0;
        min-width: 80%; 
        margin-top: 0 !important;
        margin-bottom: 40px;
    ",
    "layout-img-footer" => "width: 100%;  margin-top: 40px;",
    "ilustration" => "
        width: 30%;
        margin: 0px auto; 
    ",
    "body" => "
        padding: 0px !important; 
        margin: 0 !important; 
        font-family: \"Poppins\", sans-serif !important; 
        font-size: 18px;
    ",
    "body > div" => "background: #FFF; text-align: center;",
    "h1" => "color: {$colors['default']}; font-size: 22px; margin-bottom: 20px;",
    "h2" => "color: {$colors['black3']}; font-size: 20px; margin-top: 0;",
    "p" => "color: {$colors['black3']}; margin: 0; font-size: 13px;",
    "body > div > img" => "width: 10vh;",

    //content
    "content" => "padding: 0px; color: #002;",
    "strong" => "
        color: {$colors['black3']};
        font-size: 16px; 
        margin-bottom: 20px;
    ",
    "strong > b" => "color: {$colors['default']};",
    "small" => "font-size: 10px;",
    "p > b" => "color: {$colors['default']};",
    "btn" => "
        background: {$colors['default']}; 
        color: {$colors['white']}; 
        font-weight: 400; 
        font-size: 12px; 
        padding: 10px 15px;
        text-decoration:none; 
        border-radius: 5px;
        margin: 10px; 
        cursor: pointer;
    ",
    "table" => "
        box-shadow: 1px 1px 10px {$colors['black_shadow']}; 
        border-radius: 8px; margin: 0 auto;
        border-collapse: collapse;
        max-width: 80%;
        padding: 10px;
    ",
    "thead" => "
        background-color: {$colors['default']}; 
        color: {$colors['black3']}; 
        font-size: 12px; text-align: center; 
        font-weight: bold;
        padding: 2px; 
        background: #333;
        font-size: 16px;
        color: #fff;
        vertical-align: middle;
        font-weight: 400;
        text-transform: capitalize;
    ",
    "tr_td_th" => "
        padding: 10px 4px; 
        font-size: 9.8px !important;
        vertical-align: middle;
        font-weight: 300;
        text-transform: capitalize;
        line-height: 1;
        white-space: nowrap;
    ",
    "first_th" => "border-radius: 2px 0 0 0;",
    "last_th" => "border-radius: 0 2px 0 0;",
    "tr" => "font-size: 12px; text-align: center; padding: 10px; font-size: 12px;",
    "tr2" => "background: {$colors['white2']}; font-size: 12px;",
    "title" => "
        color: {$colors['black2']}; 
        font-size: 16px 
        padding: 0;
        margin-block-start: 0px;
        margin-block-end: 0px;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
    ",

    //footer
    "footer" => "padding: 40px; color: #002; font-size: 12px;",
    "footer_text" => "<b>ENGETÉCNICA ENGENHARIA E CONSTRUÇÃO LTDA</b>, Rua João Bettega, n.1160, Portão, Curitiba-PR | Fone: (41) 4040-4676"
];
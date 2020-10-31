<?php
/*
     Логика подключения нужного шаблона
 */
namespace MyBlog\View;

class View
{
    private $templatesPath;

    private $extraVars = [];

    public function __construct(string $templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    public function setVar(string $name, $value): void
    {
        $this->extraVars[$name] = $value;
    }

    //Подключает шаблон
    public function renderHtml(string $templateName, array $vars = [], int $code = 200)
    {
        http_response_code($code); //Код ответа
        extract($this->extraVars);
        extract($vars); //Создаются переменные из значений переданного массива $vars. Их используем в шаболоне(templates/)

        ob_start();
        include $this->templatesPath . '/' . $templateName;
        $buffer = ob_get_contents();
        ob_end_clean();

        echo $buffer;
    }
}
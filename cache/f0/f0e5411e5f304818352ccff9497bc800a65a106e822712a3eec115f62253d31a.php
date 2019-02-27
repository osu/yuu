<?php

/* home.twig */
class __TwigTemplate_25eaf6a8f0a1721fe40fcd92469e914167d9cb7e06875d40ebee8aead0a6703e extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "home.twig", 1);
        $this->blocks = [
            'page' => [$this, 'block_page'],
        ];
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_page($context, array $blocks = [])
    {
        // line 5
        echo "<div class=\"select is-primary\">
\t<select name=\"dtime\">
\t\t<option value=\"24\">Delete time : 1 day</option>
\t\t<option value=\"744\">Delete time : 1 month</option>
\t\t<option value=\"8928\">Delete time : 1 year</option>
\t</select>
</div>
<h2 class=\"subtitle\">
\t<button id=\"upload\" class=\"button is-primary is-rounded\">
\t\tClick or drop files here
\t</button>
\t<input type=\"file\" id=\"input\" name=\"upload[]\" multiple hidden>
</h2>
<h2 class=\"subtitle\">
\tYou can upload up to ";
        // line 19
        echo twig_get_attribute($this->env, $this->source, ($context["global"] ?? null), "maxsize", []);
        echo "mb per file
</h2>
<ul class=\"box uploadedFiles\" style=\"display: none\">
</ul>
";
    }

    public function getTemplateName()
    {
        return "home.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  51 => 19,  35 => 5,  32 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "home.twig", "C:\\Users\\Sylvain\\Documents\\Mes Projets\\yuu.sh\\templates\\home.twig");
    }
}

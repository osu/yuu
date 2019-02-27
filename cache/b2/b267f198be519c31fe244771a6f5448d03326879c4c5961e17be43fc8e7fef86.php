<?php

/* layout.twig */
class __TwigTemplate_515e8eb59d594907ec482641c1d78b9d1fdc309da5cb6870e5af07d36204d41f extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
\t<meta charset=\"UTF-8\">
\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
\t<meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
\t<title>";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["global"] ?? null), "name", []), "html", null, true);
        echo " :: ";
        echo twig_escape_filter($this->env, ($context["page"] ?? null), "html", null, true);
        echo "</title>
\t<link rel=\"stylesheet\" href=\"/css/style.css\">
\t<script src=\"https://code.jquery.com/jquery-3.3.1.min.js\"></script>
\t<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js\"></script>
\t<script src=\"/js/particles.min.js\"></script>
\t<script src=\"/js/script.min.js\"></script>
\t<meta property=\"og:title\" content=\"";
        // line 13
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["global"] ?? null), "name", []), "html", null, true);
        echo " :: ";
        echo twig_escape_filter($this->env, ($context["page"] ?? null), "html", null, true);
        echo "\">
\t<meta property=\"og:site_name\" content=\"";
        // line 14
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["global"] ?? null), "name", []), "html", null, true);
        echo "\">
\t<meta property=\"og:url\" content=\"https://yuu.sh\">
\t<meta property=\"og:description\" content=\"";
        // line 16
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["global"] ?? null), "subtitle", []), "html", null, true);
        echo "\">
\t<meta property=\"og:type\" content=\"website\">
\t<meta property=\"og:image\" content=\"https://yuu.sh/img/waifu_logo/1.png\">
</head>
<body>
\t<div id=\"particles-js\"></div>
\t<video src=\"/video/background.mp4\" class=\"background\" autoplay muted></video>

    <section class=\"hero is-fullheight is-dark has-text-centered\">
        <div class=\"hero-body\">
            <div class=\"container\">
                ";
        // line 28
        echo "\t\t\t\t<img src=\"\" alt=\"Logo of a waifu\" id=\"logo\">
\t\t\t\t<h1 class=\"title\">
\t\t\t\t\t";
        // line 30
        echo twig_get_attribute($this->env, $this->source, ($context["global"] ?? null), "name", []);
        echo "
\t\t\t\t</h1>
\t\t\t\t<h2 class=\"subtitle\">
\t\t\t\t\t";
        // line 33
        echo twig_get_attribute($this->env, $this->source, ($context["global"] ?? null), "subtitle", []);
        echo "
\t\t\t\t</h2>
\t\t\t\t";
        // line 36
        echo "                ";
        $this->displayBlock("page", $context, $blocks);
        echo "
                ";
        // line 38
        echo "\t\t\t\t<ul class=\"nav-speed\">
\t\t\t\t\t";
        // line 39
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["global"] ?? null), "pages", []));
        foreach ($context['_seq'] as $context["_key"] => $context["page"]) {
            // line 40
            echo "\t\t\t\t\t<li><a href=\"";
            echo twig_get_attribute($this->env, $this->source, $context["page"], "url", []);
            echo "\">";
            echo twig_get_attribute($this->env, $this->source, $context["page"], "name", []);
            echo "</a></li>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['page'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        echo "\t\t\t\t</ul>
\t\t\t\t";
        // line 44
        echo "            </div>
        </div>
    </section>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  108 => 44,  105 => 42,  94 => 40,  90 => 39,  87 => 38,  82 => 36,  77 => 33,  71 => 30,  67 => 28,  53 => 16,  48 => 14,  42 => 13,  31 => 7,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "layout.twig", "C:\\Users\\Sylvain\\Documents\\Mes Projets\\yuu.sh\\templates\\layout.twig");
    }
}


<!-- INICIO = Componentes/librerias.php -->

        <!-- ESTILOS  PRINCIPALES CSS -->
        <link rel="stylesheet" type="text/css" href="css/main.css"/>
		<!-- JAVASCRIPT HISTORIAL -->
		<script type="text/javascript"  src="js/unFocus-History/unFocus-History-p.js"></script>

        <!-- SCRIPTS  PRINCIPALES JS -->
        <script type="text/javascript"  src="js/jquery-1.7_min.js"></script>		
        
        <!-- SCRIPTS/ESTILOS CSS/JS -->
        <link rel="stylesheet" type="text/css" href="../../lib/jquery/jquery.validity.1.2.0/jquery.validity.css"/>

        <script type="text/javascript"  src="../../lib/jquery/jquery.validity.1.2.0/jQuery.validity.min.js"></script>

        <!--MENU DESPLEGABLE-->
        <script type="text/javascript">
            $(document).ready(function(){ // Script del Navegador
			 
                $("ul.subnavegador").hide();
                $("a.desplegable").toggle(
                function() {
                    $(this).parent().find("ul.subnavegador").slideDown('slow');
                },
                function() {
                    $(this).parent().find("ul.subnavegador").slideUp('slow');
                }
            );
            });
        </script>
<!-- FINAL = Componentes/librerias.php -->
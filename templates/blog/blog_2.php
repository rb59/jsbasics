<?php
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                                        #|
#|       JsBasics by  » Daniel, Carlos y Ronald «         #|
#|    Copyright © 2020. Todos los derechos reservados.    #|
#|														  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|

global $Functions; 
global $TplClass;
global $db;
if(isset($_GET['id'])){
    $id = $Functions->Filter($_GET['id']);
    $dn2 = $db->query("SELECT * FROM blog WHERE idblog = '{$id}' AND status = '1' ");
    $d2 = $dn2->fetch_array();
}
?>
    <link href="../ckeditor/plugins/codesnippet/lib/highlight/styles/monokai.css" rel="stylesheet">
    <script src="../ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <section class="bg-light py-1">
        <div class="container mt-n10 mb-5" data-aos="fade-up" data-aos-delay="50">
            <div class="card">
                <div class="card-body">
                    <div class="sbp-preview">
                        <div class="sbp-preview-text imgupresponsive">
                            <p class="blog-content_date"><?php echo $Functions->Date(date("F d, Y", strtotime($Functions->Filter($d2['fecha']))))?></p>
                            <h1 class="blog-content_title"><?php echo $Functions->Filter($d2['titulo_blog']);?></h1>
                            <div class="blog-content_divider"><div></div></div>
                            <div class="">
                            <?php echo $Functions->Filter($d2['contenido']);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
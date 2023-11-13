<!DOCTYPE html>
<html>
    <head>
        <style>
            /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm;
            }
            @media print {
                html, body {
                    height: 100%;
                }
            }
            body {
                display: block;
                position: center;
                margin-top: 0cm;
                margin-left: 0.6cm;
                margin-right: 0.7cm;
                margin-bottom: 1cm;
                font-size: 0.6em;
                font: 16pt/1.5 'Raleway','Cambria', sans-serif;
                font-weight: 300;
                background:  #fff;
                color: black;
                -webkit-print-color-adjust:  exact;
                /*border:1px solid black;*/
            }
            section{
                margin-top: -2px;
                margin-bottom: -2px;
            }
            div{
            .droite{
                text-align:right;
                font-size: 0.75em;
                margin-top:-30px;
            }
            .gauche{
                text-align:left;
                font-size: 0.8em;
            }
            hr{
                border-top: 1px dotted red;
            }
            nav{
                /*border:1px solid black;*/
                margin-top:30px;
                float: center;
            }
            table {
                font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 5px;
            }
            td, th {
                text-align: center;
                font-size: 0.75em;
            }
            header{
                /*margin-left: auto;
                margin-right: auto;*/
            }
        </style>
    <head>
    <body>
        <div>
            <section class="droite" style="text-align: center;font-size: 20px;">
                <header style="margin-top : 50px">
                     TICKET <?php echo e($module ? $module["nom"] : ""); ?>

                </header>
                <br>

                <div style="font-size: 10px;font-weight:bold">
                    <img src="<?php echo e(asset('app-assets/assets/images/LOGO.PNG')); ?>" style="width: 80px; margin-top: 10px;"> <br>
                    <!--IMAARA-->
                </div>

                <div style="margin:10px 0">
                            Centre Medico Social
                    CHIFAA No 023 Parcelles Assainies-Unité 24
                </div>
                <div style="margin:10px 0">
                    TEL : 33 821 25 12 / 77 270 72 22
                </div>
                ************************
                
                <dt  style="margin:10px 0">Date : <?php echo e($created_at); ?></dt>
                <div style="margin:7px 0">
                Medecin : Dr <?php echo e(isset($medecin) ? $medecin["nom"] : "NEANT"); ?>

                </div>
                <div style="margin:7px 0">
                    Patient(e) : <?php echo e(isset($nom_complet) ? \App\Models\Outil::toUpperCase($nom_complet) : "No ref"); ?>

                </div>
                ************************

                <dt  style="font-size: 18px;font-weight:bold">Vente N°<?php echo e($id); ?></dt>
            </section>
            <section  style="margin-top : 30px;font-size: 15px;">
                <table>
                    <tbody>
                    <!-- <tr>
                        <td style="width: 10%">Qte </td>
                        <td style="width: 60%">Produit </td>
                        <td style="width: 20%">Montant </td>
                    </tr> -->
                        <?php echo e($montant = 0); ?>

                        <?php $__currentLoopData = $element_services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="padding-left: 15px;"><?php echo e($element_service["type_service"]["nom"] ? $element_service["type_service"]["nom"] : ""); ?></td>
                                <td style="padding-left: 15px;"><?php echo e($element_service["type_service"]["prix"] ? \App\Models\Outil::formatPrixToMonetaire($element_service["type_service"]["prix"], false, false) : ""); ?></td>
                                <?php echo e($element_service["type_service"]["prix"] ? $montant = $montant + $element_service["type_service"]["prix"] : ""); ?>

                                <td style="padding-left: 15px">
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </section>
            <div style="overflow: hidden;margin-top : 15px">***********************************************</div>
            <section  style="overflow: hidden;margin-bottom : 20px;font-size: 20px;">
                <table>
                    <tbody>
                    <tr>
                        <td style="text-align:left">Total </td>
                        <td style="padding : 10px 0;text-align:left">  <?php echo e(\App\Models\Outil::formatPrixToMonetaire($montant, false, true)); ?></td>
                    </tr>
                    </tbody>
                </table>
            </section> 
        </div>
    </body>
  <footer>
        
        <!-- <p style="font-size:10px">Vous avez été servi par:  <?php echo e($user ? $user["name"] : " "); ?> </p> -->
        <p style="font-size:10px">Votre Santé Notre Priorité </p>
</footer>
</html><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/alfalah_caisse_back/resources/views/pdf/ticket-service.blade.php ENDPATH**/ ?>
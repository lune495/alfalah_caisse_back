    <html>
        <head>
            <title>
                <?php echo $__env->yieldContent('title'); ?>
            </title>
            <style>
               /* styles.css */

/* Supprimez les marges du corps du document */
body {
    margin: 0;
    padding: 0;
}

/* Style pour les tableaux */
.table-container {
    display: flex;
    justify-content: space-between;
}
 /* Style pour centrer le titre */
    .situation-heading {
        text-align: center;
    }

/* Style pour chaque tableau */
.custom-table {
    border-collapse: collapse;
    width: 100%; /* Ajustez la largeur selon vos besoins, par exemple, 50% */
}

.custom-table th {
    background-color: #007bff; /* Couleur de l'en-tête */
    color: white;
    font-weight: bold;
    text-align: center;
    padding: 10px;
}

.custom-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

/* Style pour la ligne colorée */
.colorful-row {
    background-color: #f2f2f2;
}

/* Style pour le badge */
.badge {
    background-color: #007bff;
    color: white;
    padding: 5px;
    border-radius: 5px;
    font-weight: bold;
    font-size: 14px;
}

            </style>
        </head>
        <body>

            <header class = "mb-4" >
                <div class="header text-center">
                <!-- <img src="<?php echo e(asset('app-assets/assets/images/LOGO.png')); ?>" style="width: 80px; margin-top: 10px;"> <br> -->
                </div>
            </header>
            <div class="mt-4">
                <?php echo $__env->yieldContent('content'); ?>
            </div>

            <!--Footer page-->
            <div class="footer">
                 <!-- 
                    <p> 
                        <i>La confiance n'exclut pas le controle, veuillez verifier votre facture à la réception
                        <br>
                        
                    </p>
                
                 -->
                <!-- <div style="display:inline-flex" style="font-size:10px;">
                    <p class="text-left" style="font-size:10px;"> NINEA: 00455 90 26 - Dakar</p> 
                </div> -->
            </div>
        </body>
    </html>
<?php /**PATH /var/www/html/alfalah_caisse_back/resources/views/pdf/layouts/layout-export2.blade.php ENDPATH**/ ?>
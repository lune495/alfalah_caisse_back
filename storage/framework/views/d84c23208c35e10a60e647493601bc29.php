<?php $__env->startSection('title', "Situation Generale"); ?>
<?php $__env->startSection('content'); ?>

<h4 class="situation-heading">Situation Generale du <?php echo e($derniere_date_fermeture); ?> au <?php echo e($current_date); ?></h4>
<div class="table-container">
    <!-- Tableau de gauche (RECETTE) -->
    <div class="table-wrapper left">
        <h4>RECETTE</h4>
        <table class="custom-table">
            <!-- En-tête -->
            <tr>
                <th>DESIGNATION</th>
                <th>MONTANT</th>
            </tr>
            <!-- Contenu -->
            <!-- ... Votre boucle foreach existante ... -->
            <?php echo e($montant_total = 0); ?>

            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sum): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($montant_total = $montant_total + $sum->total_prix); ?>

                <tr>
                    <td><center> <?php echo e(\App\Models\Outil::toUpperCase($sum->designation)); ?></center></td>
                    <td><?php echo e(\App\Models\Outil::formatPrixToMonetaire($sum->total_prix, false, false)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td colspan="2">
                    <div>
                        <p class="badge" style="line-height:15px;">Total</p>
                        <p style="line-height:5px;text-align:center"><?php echo e(\App\Models\Outil::formatPrixToMonetaire($montant_total, false, false)); ?></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tableau de droite (DEPENSE) -->
    <div class="table-wrapper right">
        <h4>DEPENSE</h4>
        <table class="custom-table">
            <!-- En-tête -->
            <tr>
                <th>Nature</th>
                <th>MONTANT</th>
            </tr>
            <!-- Contenu -->
            <!-- ... Votre boucle foreach existante pour les dépenses ... -->
            <?php echo e($montant_total_depense = 0); ?>

            <?php $__currentLoopData = $depenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($montant_total_depense = $montant_total_depense + $dep->montant); ?>

                <tr>
                    <td><center> <?php echo e(\App\Models\Outil::premereLettreMajuscule($dep->nom)); ?></center></td>
                    <td> <center><?php echo e($dep->montant); ?></center></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td colspan="2">
                    <div>
                        <p class="badge" style="line-height:15px;">Total</p>
                        <p style="line-height:5px;text-align:center"><?php echo e(\App\Models\Outil::formatPrixToMonetaire($montant_total_depense, false, false)); ?></p>
                    </div>
                </td>
            </tr>
            <!-- Ajoutez la ligne colorée si nécessaire -->
                <tr class="colorful-row">
                    <td colspan="2" style="padding-top: 10px; font-size: 15px">
                        <p>Solde Caisse :</p>
                        <p style="font-weight: bold; font-size: 20px"><?php echo e(\App\Models\Outil::formatPrixToMonetaire($montant_total - $montant_total_depense, false, true)); ?></p>
                    </td>
                </tr>
        </table>
    </div>
</div>

<!-- ... Le reste de votre modèle ... -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('pdf.layouts.layout-export2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/alfalah_caisse_back/resources/views/pdf/situation-pdf.blade.php ENDPATH**/ ?>
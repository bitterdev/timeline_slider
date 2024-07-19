<?php

use Concrete\Core\File\File;
use Concrete\Core\Entity\File\File as FilEntity;
use Concrete\Core\Html\Image;

defined('C5_EXECUTE') or die('Access denied');

/** @var array $items */
?>

<?php foreach ($items as $item) { ?>
    <div class="timeline-container d-none" data-year="<?php echo h($item["year"]); ?>">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <?php
                $f = File::getByID($item["fID"]);
                if ($f instanceof FilEntity) {
                    $image = new Image($f);
                    echo $image->getTag();
                }
                ?>
            </div>

            <div class="col-md-6 col-sm-12">
                <h3>
                    <?php echo $item["subtitle"]; ?>
                </h3>

                <h2>
                    <?php echo $item["title"]; ?>
                </h2>

                <p>
                    <?php echo nl2br($item["description"]); ?>
                </p>
            </div>
        </div>
    </div>
<?php } ?>

<div class="timeline-slider">
    <div class="hr"></div>

    <?php foreach ($items as $item) { ?>
        <a href="javascript:void(0);" data-year="<?php echo h($item["year"]); ?>" class="btn-year">
            <?php echo $item["year"]; ?>
        </a>
    <?php } ?>
</div>
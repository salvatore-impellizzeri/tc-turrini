<?php
use Cake\Core\Configure;
$languages = array_merge([Configure::read('Setup.default_language')], Configure::read('Setup.languages'));
?>

<?php if (count($languages) > 1):
    $pageUrl = $this->Frontend->getCakeUrl();
    ?>
    <div class="languages" data-languages>
        <div class="languages__toggler" data-languages-toggler>
            <span class="languages__label">
                <?= ACTIVE_LANGUAGE ?>
            </span>
            <span class="languages__icon">
                <?= $this->Frontend->svg('icons/dropdown.svg') ?>
            </span>
        </div>

        <div class="languages__dropdown">
            <?php foreach ($languages as $lang):
                $homeLink = $lang == DEFAULT_LANGUAGE ? '/' : '/' . $lang . '/';
                $extraClass = $lang == ACTIVE_LANGUAGE ? 'languages__link--active' : '';
                ?>
                <a class="languages__link <?php echo $extraClass ?>" href="<?= IS_HOME ? $homeLink : $this->Frontend->getLanguageUrl($pageUrl, $lang) ?>">
                    <?php echo $lang ?>
                </a>
            <?php endforeach; ?>
        </div>
        
    </div>
<?php endif; ?>

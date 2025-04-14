<?php
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Query;
use Cake\Utility\Security;

if (empty($id)) return;

if (!empty($showTitle)){
    $form = FactoryLocator::get('Table')->get('Contacts.ContactForms')->get($id);
    if (empty($form)) return;
}


$fields = FactoryLocator::get('Table')->get('Contacts.ContactFields')->findByContactFormId($id)->all();
if (empty($fields->count())) return;
?>

<?= $this->Form->create(null, [
    'class' => 'antispam contact-form contact-form--'.$id,
    'url' => ['plugin' => 'contacts', 'controller' => 'contacts', 'action' => 'req']
]); ?>

    <?php
    //campi nascosti per validazione
    echo $this->Form->hidden('contact_form_id', ['value' => $id]);
    echo $this->Form->hidden('contact_form_hash', ['value' => Security::hash($id, 'md5', true)]);
    ?>

    <?php if (!empty($showTitle)) echo $this->Html->tag($showTitle, $form->title, ['class' => 'contact-form__title']) ?>

    <div class="contact-form__inputs">
        <?php  
        foreach ($fields as $field) {
            switch ($field->type) {
                case 'text':
                case 'email':
                case 'tel':    
                case 'number': 
                case 'textarea':    
                    echo $this->Form->control($field->field, [
                        'label' => $field->label,
                        'type' => $field->type,
                        'placeholder' => $field->label,
                        'required' => $field->required,
                        'extraClass' => 'input--'.$field->id,
                        'escape' => false
                    ]);
                    break;
                case 'checkbox':         
                    echo $this->Form->control($field->field, [
                        'label' => $this->Html->tag('span', $field->label),
                        'type' => $field->type,
                        'required' => $field->required,
                        'extraClass' => 'input--'.$field->id,
                        'escape' => false
                    ]);
                    break;
                case 'select':    
                    $options = explode("\n", $field->multiple_values);     
                    echo $this->Form->control($field->field, [
                        'label' => false,
                        'empty' => $field->label . ($field->required ? ' *' : ''),
                        'type' => $field->type,
                        'required' => $field->required,
                        'extraClass' => 'input--'.$field->id,
                        'escape' => false,
                        'options' => array_combine($options, $options),
                    ]);
                    break;
                case 'date':
                    echo $this->Form->control($field->field, [
                        'label' => false,
                        'type' => 'text',
                        'placeholder' => $field->label.($field->required ? ' *' : ''),
                        'required' => $field->required,
                        'extraClass' => 'text-date input--'.$field->id,
                        'escape' => false,
                        'onfocus' => "(this.type='date')",
                        'onblur' => "(this.type='text')",
                        'min' => date('Y-m-d')
                    ]);
                    break;
                case 'hidden':
                    echo $this->Form->hidden($field->field, [
                        'value' => ${$field->field} ?? ''
                    ]);
                    break;
            }

        }
        ?>
            
    </div>
    <div class="contact-form__footer">
        <button class="button <?= $buttonExtraClass ?? '' ?>">
            <span><?= __d('contacts', 'send'); ?></span>
            
        </button>
    </div>

<?= $this->Form->end() ?>

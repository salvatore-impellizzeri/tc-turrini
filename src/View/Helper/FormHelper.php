<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper\FormHelper as Helper;

class FormHelper extends Helper
{


	public function create($context = null, array $options = []): string
    {

		if(!empty($context->_translatableFields)) $this->setConfig('translatableFields', $context->_translatableFields);

		$options += [
			'type' => 'file',
			'id' => 'superForm',
			'novalidate' => true
		];

		return parent::create($context, $options);
	}

    public function control(string $fieldName, array $options = []): string
    {

		// se siamo in una traduzione, imposto come disabled tutti i campi NON traducibili
		// bisogna aggiungere questa riga subito dopo il form create nella vista
		// if(!empty($translatableFields)) $this->Form->setConfig('translatableFields', $translatableFields);

		$translatableFields = $this->getConfig('translatableFields');
        
		if(!empty($translatableFields)) {
            if (preg_match('/^(.*)\..*\.(.*)$/', $fieldName, $match)) {
                //modelli collegati
                if (empty($translatableFields[$match[1]]) || !in_array($match[2], $translatableFields[$match[1]])) {
                    $options['disabled'] = true;
                    $options['extraClass'] = empty($options['extraClass']) ? 'disabled': $options['extraClass'] . ' disabled';
                }
            } else {    
                //modello principale
                if(!in_array($fieldName, $translatableFields)) {
                    $options['disabled'] = true;
                    $options['extraClass'] = empty($options['extraClass']) ? 'disabled': $options['extraClass'] . ' disabled';
                }
            }
			
		}

		if (!empty($options['extraClass'])) {
            $options['templateVars']['extraClass'] = $options['extraClass'];
            unset($options['extraClass']);
        }

		if (!empty($options['icon'])) {
            $options['templateVars']['icon'] = $options['icon'];
            unset($options['icon']);
        }


        // TODO -> verifica comportamento su traduzioni
        if (!empty($options['addOption'])) {
        
            $options['templateVars']['data'] = 'data-select-add';
            $newOptionFieldName = $options['newOptionFieldName'] ?? '_'.$fieldName;
            $options['templateVars']['extraInput'] = '<div data-select-add-new>'.$this->text($newOptionFieldName, ['value' => '', 'placeholder' => $options['newOptionPlaceholder'] ?? '']).'<span data-select-add-close class="material-symbols-rounded">close</span></div>';
            $selectOptions = !empty($options['options']) ? (!is_array($options['options']) ? $options['options']->toArray() : $options['options']): [];
            $newOptionIndex = $options['newOptionIndex'] ?? -1;
            $selectOptions[$newOptionIndex] = __d('global', 'new select option');
            $options['options'] = $selectOptions;
        }

        return parent::control($fieldName, $options);

    }

	public function editor(string $fieldName, array $options = []): string
	{
		$defaults = ['icon' => 'notes'];
		return $this->Html->div('editor', $this->control($fieldName, array_merge($defaults, $options)));
	}

	public function inlineEditor(string $fieldName, array $options = []): string
	{
		$defaults = ['icon' => 'short_text', 'type' => 'textarea'];

		return $this->Html->div('inline-editor', $this->control($fieldName, array_merge($defaults, $options)));
	}
	
	public function geocodeMap($field, $modelName)
	{
		
		$classes = 'input map input-gmap';
	
		$output = '<div class="' . $classes . '">';
		$errorField = $this->error($field);
		if(!empty($errorField)) $output .= '<div class="error-message">' . $this->Form->error($field) . '</div>';
		$output .= $this->control($field, ['placeholder' => 'Cerca la posizione sulla mappa', 'id' => 'GeocodeAddress']);
		$output .= $this->hidden($field, ['data-geocode-value' => '']);
		$output .= $this->Html->tag('div', '' ,['id' => 'map-canvas']);
		$output .= '</div>';
		return $output;
	}

 
}

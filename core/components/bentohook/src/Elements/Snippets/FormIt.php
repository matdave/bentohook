<?php

namespace BentoHook\Elements\Snippets;

use BentoHook\Services\Bento;
use MatDave\MODXPackage\Elements\Snippet\Snippet;

class FormIt extends Snippet
{
    private $debug = false;
    public function run()
    {
        $hook = $this->scriptProperties['hook'];
        $values = $hook->getValues();
        $this->debug = $this->modx->getOption('debug', $this->scriptProperties, false);
        if (!$this->debug) {
            $this->debug = $this->modx->getOption('bentoDebug', $hook->formit->config, false);
        }
        try {
            $bento = new Bento($this->service);
        } catch (\Exception $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'BentoHook: ' . $e->getMessage());
            if ($this->debug) {
                return false;
            }
            return true;
        }

        $emailField = $this->modx->getOption('bentoEmail', $hook->formit->config, 'email');

        $optinField  = $hook->formit->config['bentoOptin'];
        $optoutField  = $hook->formit->config['bentoOptout'];
        $fieldsField = $hook->formit->config['bentoFields'];

        $email = $this->getField($emailField , $values);
        $optin = $this->getField($optinField , $values);
        $optout = $this->getField($optoutField, $values);
        $fields = $this->getProperties($fieldsField, $values);

        if (empty($email)) {
            if ($this->debug) {
                $hook->modx->log(modX::LOG_LEVEL_ERROR, 'BentoHook: Email is required');
                return false;
            }
            return true;
        }

        if (empty($optin) && !empty($optinField)) {
            if (!empty($optout)) {
                $remove = $bento->removeSubscriber($email);
                if ($remove && $this->debug) {
                    $this->modx->log(modX::LOG_LEVEL_INFO, 'BentoHook: remove response ' . json_encode($remove));
                }
                return true;
            } else {
                if ($this->debug) {
                    $hook->modx->log(modX::LOG_LEVEL_ERROR, 'BentoHook: Optin is required');
                    return false;
                }
                return true;
            }
        }

        $subscribe = $bento->addSubscriber($email, $fields);
        if ($subscribe && $this->debug) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'BentoHook: subscribe response ' . json_encode($subscribe));
        }
        return true;
    }

    /**
     * @param mixed $field string to compare against)
     * @param array $values array("key1"=>"value1")
     */

    public function getField($field = null, array $values = [])
    {
        $field = str_replace('[[+', '', $field);
        $field = str_replace('[[!+', '', $field);
        $field = str_replace(']]', '', $field);
        return (array_key_exists($field,$values)) ? $values[$field] : $field;
    }

    /**
     * @param mixed $fields "key1==property1,key2==property2,property3" or array("key1"=>"property1", "key2"=>"property2")
     * @param array $values array("key1"=>"value1")
     */

    public function getProperties($fields = null, array $values = [])
    {
        $properties = [];
        if (!is_array($fields)) {
            $fieldsNew = [];
            $fields = explode(',', $fields);
            foreach ($fields as $field) {
                $field = explode('==', $field);
                $fieldsNew[$field[0]] = ($field[1]) ? $field[1] : $field[0];
            }
            $fields = $fieldsNew;
        }
        if (!empty($fields)) {
            foreach ($fields as $k => $v) {
                $properties[$k] = $values[$v];
            }
            return $properties;
        } else {
            return $values;
        }
    }
}
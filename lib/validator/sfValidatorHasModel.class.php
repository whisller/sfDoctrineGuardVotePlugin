<?php
class sfValidatorHasModel extends sfValidatorBase
{
    protected function configure($options = array(), $messages = array())
    {
        $this->addMessage('invalid', 'Specified model does not exist');
    }

    protected function doClean($value)
    {
        if (!class_exists($value)) {
            throw new sfValidatorError($this, 'invalid');
        }

        return $value;
    }
}
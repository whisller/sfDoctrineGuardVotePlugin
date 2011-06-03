<?php
/**
 * Validator is checking that your model exist.
 *
 * @package    sfDoctrineGuardVotePlugin
 * @subpackage lib.validator
 * @author     Daniel Ancuta <whisller@gmail.com>
 */
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
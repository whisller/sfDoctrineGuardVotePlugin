<?php
class sfValidatorHasVoteObject extends sfValidatorBase
{
    protected function configure($options = array(), $messages = array())
    {
        $this->addMessage('invalid', 'Specified object does not exist');
    }

    protected function doClean($value)
    {
        $request = sfContext::getInstance()->getRequest();

        $objectName = $request->getParameter('object');

        $objectTableInstance = call_user_func($objectName.'Table::getInstance');
        $object = $objectTableInstance->findOneById($value);

        if (false === ($object instanceof $objectName)) {
            throw new sfValidatorError($this, 'invalid');
        }

        return $value;
    }
}
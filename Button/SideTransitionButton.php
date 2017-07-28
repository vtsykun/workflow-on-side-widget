<?php

namespace Oro\Bundle\TaskBundle\Button;

use Oro\Bundle\WorkflowBundle\Button\AbstractTransitionButton;

class SideTransitionButton extends AbstractTransitionButton
{
    /**
     * {@inheritdoc}
     */
    public function getTemplateData(array $customData = [])
    {
        $entityId = $this->getButtonContext()->getEntityId();

        if (is_array($entityId)) {
            $entityId = reset($entityId);
        }

        $workflowItem = $this->workflow->getWorkflowItemByEntityId($entityId);
        $templateData = parent::getTemplateData($customData);

        $data = array_merge_recursive(
            $templateData,
            [
                'routeParams' => [
                    'workflowItemId' => $workflowItem ? $workflowItem->getId() : null
                ]
            ]
        );

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroup()
    {
        return 'side-widget';
    }
}

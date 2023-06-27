<?php

class WP_Node_Export
{
    public $fieldTypes = [];
    public $exportData;
    public $total;

    public function getFieldTypes()
    {
        $node = Drupal::entityTypeManager()->getStorage('commerce_product')->load(132);
        $nodeFields = $this->getValidFieldList($node->getEntityTypeId(), $node->bundle());
        foreach ($nodeFields as $nodeField) {
            $field = $node->{$nodeField};
            $fieldDefinition = $field->getFieldDefinition();
            $this->fieldTypes[$nodeField] = $fieldDefinition->getType();
        }
        return $this->fieldTypes;
    }

    public function export($entityTypeId = 'commerce_product')
    {
        $entityQuery = Drupal::entityTypeManager()->getStorage($entityTypeId)->getQuery();
        $entityQuery->condition('type', 'servicios_y_especialidades');
        $nodes = $entityQuery->execute();
        $nodes = Drupal::entityTypeManager()->getStorage($entityTypeId)->loadMultiple($nodes);
        $this->total = count($nodes);
        foreach ($nodes as $node) {
            $this->exportData[] = $this->getNodeData($node);
        }
    }

    public function exportSingle()
    {
        // $id = 132; // cardiologia
        $id = 8; // quimioterapia
        $product = Drupal::entityTypeManager()->getStorage('commerce_product')->load($id);
        $data = $this->getNodeData($product);
        file_put_contents(__DIR__ . '/quimioterapia.json', json_encode($data));
        return $data;
    }

    /**
     * @param \Drupal\Core\Entity\EntityInterface $node
     */
    public function getNodeData($node)
    {
        $nodeData = [];
        $nodeFields = $this->getValidFieldList($node->getEntityTypeId(), $node->bundle());
        foreach ($nodeFields as $nodeField) {
            $field = $node->{$nodeField};
            $fieldDefinition = $field->getFieldDefinition();
            $type = $fieldDefinition->getType();
            $nodeData[$nodeField] = $field->getString();

            if ($type === 'image') {
                $nodeData[$nodeField] = $this->getImgUrl($field);
            }

            // @var Drupal\paragraphs\Entity\ContentEntityBase
            /**
             * @var \Drupal\Core\Entity\EntityInterface
             */
            $entity = $field->entity;

            if ($entity) {
                $entityTypeId = $entity->getEntityTypeId();
                $unallowed = ['commerce_product_type', 'user_role', 'profile_type', 'commerce_store_type'];

                if ($type === 'entity_reference_revisions' && !in_array($entityTypeId, $unallowed)) {
                    $nodeData[$nodeField] = $this->getNodeDataEntityReferenceRevision($entity);
                }

                if ($type === 'entity_reference' && !in_array($entityTypeId, array_merge($unallowed, ['paragraphs_type', 'node_type']))) {
                    $nodeData[$nodeField] = [];
                    foreach (array_column($field->getValue(), 'target_id') as $id) {
                        $entity = Drupal::entityTypeManager()->getStorage($entityTypeId)->load($id);
                        $nodeData[$nodeField][$id] = [];
                        $nodeData[$nodeField][$id] = $this->getNodeDataEntityReference($entity);
                    }
                }
            }
        }

        return $nodeData;
    }

    public function getNodeDataEntityReferenceRevision($node)
    {
        $nodeData = [];
        $entityTypeId = $node->getEntityTypeId();
        $bundle = $node->bundle();
        $nodeFields = $this->getValidFieldList($entityTypeId, $bundle);
        foreach ($nodeFields as $nodeField) {
            $field = $node->{$nodeField};
            $fieldDefinition = $field->getFieldDefinition();
            $type = $fieldDefinition->getType();
            $nodeData[$nodeField] = $field->getString();
            if ($type === 'image') {
                $nodeData[$nodeField] = $this->getImgUrl($field);
            }
            if ($type === 'entity_reference') {
                $entity = $field->entity;
                $nodeData[$nodeField] = [];
                if ($entity) {
                    $entityTypeId = $entity->getEntityTypeId();
                    $valid = ['commerce_product_type', 'user_role', 'profile_type', 'commerce_store_type', 'paragraphs_type', 'node_type'];
                    if (!in_array($entityTypeId, $valid)) {
                        foreach (array_column($field->getValue(), 'target_id') as $id) {
                            $entity = Drupal::entityTypeManager()->getStorage($entityTypeId)->load($id);
                            $nodeData[$nodeField][$id] = [];
                            $nodeData[$nodeField][$id] = $this->getNodeDataEntityReference($entity);
                        }
                    }
                }
            }
        }
        return $nodeData;
    }

    public function getNodeDataEntityReference($node)
    {
        $nodeData = [];
        $entityTypeId = $node->getEntityTypeId();
        $bundle = $node->bundle();
        $nodeFields = $this->getValidFieldList($entityTypeId, $bundle);
        foreach ($nodeFields as $nodeField) {
            $field = $node->{$nodeField};
            $fieldDefinition = $field->getFieldDefinition();
            $type = $fieldDefinition->getType();
            $nodeData[$nodeField] = $field->getString();
            if ($type === 'image') {
                $nodeData[$nodeField] = $this->getImgUrl($field);
            }
        }
        return $nodeData;
    }

    public function saveJsonFile()
    {
        // $myfile = fopen(__DIR__ . '/data.json', "w") or die("Unable to open file!");
        // fwrite($myfile, json_encode($data));
        // fclose($myfile);
        file_put_contents(__DIR__ . '/data.json', json_encode($this->exportData));
    }

    public function getValidFieldList(string $type = 'commerce_product', string $bundle): array
    {
        $entityFieldManager = Drupal::service('entity_field.manager');
        $nodeArticleFields = $entityFieldManager->getFieldDefinitions($type, $bundle);
        $nodeFields = array_keys($nodeArticleFields);
        $unwantedFields = [
            'comment',
            'content_translation_source',
            'content_translation_outdated',
            'sticky',
            'revision_default',
            'revision_translation_affected',
            'revision_timestamp',
            'revision_uid',
            'revision_log',
            'vid',
            'uuid',
            'promote',
        ];

        foreach ($unwantedFields as $unwantedField) {
            $unwantedFieldKey = array_search($unwantedField, $nodeFields);
            if ($unwantedFieldKey !== FALSE) {
                unset($nodeFields[$unwantedFieldKey]);
            }
        }

        return $nodeFields;
    }

    public function getImgUrl($field)
    {
        if (isset($field->target_id)) {
            $file = File::load($field->target_id);
            return $file->url();
        }
        return '';
    }
}

$exporter = new WP_Node_Export();
// echo json_encode($exporter->exportSingle());
// $exporter->export();
// $exporter->saveJsonFile();
// kint($exporter->exportData);

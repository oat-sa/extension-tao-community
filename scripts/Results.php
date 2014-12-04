<?php
/**  
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * Copyright (c) 2002-2008 (original work) Public Research Centre Henri Tudor & University of Luxembourg (under the project TAO & TAO2);
 *               2008-2010 (update and modification) Deutsche Institut für Internationale Pädagogische Forschung (under the project TAO-TRANSFER);
 *               2009-2012 (update and modification) Public Research Centre Henri Tudor (under the project TAO-SUSTAIN & TAO-DEV);
 * 
 */

namespace oat\taoCe\scripts;

class Results extends \tao_scripts_Runner
{
    const RDFTYPEPROPERTY = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#type';
    const GENERISTRUEINSTANCEURI = 'http://www.tao.lu/Ontologies/generis.rdf#True';
    const DELIVERYRESULTCLASSURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#DeliveryResult';
    const DELIVERYIDENTIFIERPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#Identifier';
    const RESULTOFSUBJECTPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#resultOfSubject';
    const RESULTOFDELIVERYPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#resultOfDelivery';
    const ITEMRESULTCLASSURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#ItemResult';
    const ITEMIDENTIFIERPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#Identifier';
    const RELATEDITEMPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#RelatedItem';
    const RELATEDTESTPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#RelatedTest';
    const RELATEDDELIVERYRESULTURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#relatedDeliveryResult';
    const VARIABLECLASSURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#Variable';
    const RESPONSEVARIABLECLASSURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#ResponseVariable';
    const OUTCOMEVARIABLECLASSURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#OutcomeVariable';
    const VARIABLEIDENTIFIERPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#Identifier';
    const VARIABLECARDINALITYPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#cardinality';
    const VARIABLEBASETYPEPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#baseType';
    const VARIABLECORRECTRESPONSEPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#correctResponse';
    const VARIABLEVALUEPROPERTYURI = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#value';
    const VARIABLEPOCHPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#variableEpoch';
    const VARIABLENORMALMAXIMUMPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#normalMaximum';
    const VARIABLENORMALMINIMUMPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#normalMinimum';
    const RELATEDITEMRESULTPROPERTYURI = 'http://www.tao.lu/Ontologies/TAOResult.rdf#relatedItemResult';
    
    public function preRun()
    {

    }

    public function run()
    {
        $outcomeRds = call_user_func(array('oat\taoOutcomeRds\model\RdsResultStorage', 'singleton'));

        $deliveryResultClass = new \core_kernel_classes_Class(self::DELIVERYRESULTCLASSURI);
        $itemResultClass = new \core_kernel_classes_Class(self::ITEMRESULTCLASSURI);
        $variableClass = new \core_kernel_classes_Class(self::VARIABLECLASSURI);
        
        // Retrieve all Delivery Results. Batch by 1.
        $batch = 1;
        $offset = 0;
        $limit = $batch;
        
        $deliveryResults = $deliveryResultClass->getInstances(false, array('offset' => $offset, 'limit' => $limit));
        
        while (count($deliveryResults) > 0) {
            
            $this->out("- Migrating Delivery Results from offset ${offset} to limit ${limit}...");
            
            foreach ($deliveryResults as $deliveryResultUri => $deliveryResult) {
                $properties = array(
                    self::DELIVERYIDENTIFIERPROPERTYURI,
                    self::RESULTOFSUBJECTPROPERTYURI,
                    self::RESULTOFDELIVERYPROPERTYURI
                );
                
                $deliveryResultValues = $deliveryResult->getPropertiesValues($properties);
                
                $noIdentifier = empty($deliveryResultValues[self::DELIVERYIDENTIFIERPROPERTYURI]);
                $noSubject = empty($deliveryResultValues[self::RESULTOFSUBJECTPROPERTYURI]);
                $noDelivery = empty($deliveryResultValues[self::RESULTOFDELIVERYPROPERTYURI]);
                
                if ($noIdentifier === false && $noSubject === false && $noDelivery === false) {
                    
                    $newResultIdentifier = current($deliveryResultValues[self::DELIVERYIDENTIFIERPROPERTYURI])->getUri();
                    $newResultSubject = current($deliveryResultValues[self::RESULTOFSUBJECTPROPERTYURI])->getUri();
                    $newResultDelivery = current($deliveryResultValues[self::RESULTOFDELIVERYPROPERTYURI])->getUri();
                    
                    $this->out("-- Migrating Delivery Result with Identifier '${newResultIdentifier}'...");
                    
                    // We have a Delivery Result to migrate.
                    
                    $outcomeRds->storeRelatedDelivery($newResultIdentifier, $newResultDelivery);
                    $outcomeRds->storeRelatedTestTaker($newResultIdentifier, $newResultSubject);
                    
                    // Retrieve all Item Results related to DeliveryResult.
                    $itemResultsPropertyFilters = array(self::RELATEDDELIVERYRESULTURI => $deliveryResultUri);
                    $itemResultsOptions = array('recursive' => false, 'like' => false);
                    $itemResults = $itemResultClass->searchInstances($itemResultsPropertyFilters, $itemResultsOptions);
                    
                    foreach ($itemResults as $itemResultUri => $itemResult) {
                        
                        $itemResultProperties = array(
                            self::ITEMIDENTIFIERPROPERTYURI,
                            self::RELATEDITEMPROPERTYURI,
                            self::RELATEDTESTPROPERTYURI
                        );
                        
                        $itemResultValues = $itemResult->getPropertiesValues($itemResultProperties);
                        
                        $noIdentifier = empty($itemResultValues[self::ITEMIDENTIFIERPROPERTYURI]);
                        $noRelatedItem = empty($itemResultValues[self::RELATEDITEMPROPERTYURI]);
                        $noRelatedTest = empty($itemResultValues[self::RELATEDTESTPROPERTYURI]);
                        
                        if ($noIdentifier === false && $noRelatedItem === false && $noRelatedTest === false) {
                            
                            $newItemResultIdentifier = current($itemResultValues[self::ITEMIDENTIFIERPROPERTYURI]);
                            $newItemResultRelatedItem = current($itemResultValues[self::RELATEDITEMPROPERTYURI])->getUri();
                            $newItemResultRelatedTest = current($itemResultValues[self::RELATEDTESTPROPERTYURI])->getUri();
                            
                            $this->out("--- Migrating Item Result with Identifier '${newItemResultIdentifier}'...");
                            
                            // Get all Variables related to this Item Result.
                            $variablePropertyFilters = array(self::RELATEDITEMRESULTPROPERTYURI => $itemResultUri);
                            $variableOptions = array('recursive' => true, 'like' => false);
                            $variables = $variableClass->searchInstances($variablePropertyFilters, $variableOptions);
                            
                            foreach ($variables as $variableUri => $variable) {
                                
                                $newVariable = $this->createVariableObject($variable);
                                $outcomeRds->storeItemVariable($newResultIdentifier, $newItemResultRelatedTest, $newItemResultRelatedItem, $newVariable, $newItemResultIdentifier);
                            }
                            
                        } else {
                            $this->out("Skipping Item Result Result with URI '${itemResultUri}'. Malformed Item Result.");
                        }
                    }
                    
                    // Now let's find test Variables.
                    $variablePropertyFilters = array(self::RELATEDDELIVERYRESULTURI => $deliveryResultUri);
                    $variableOptions = array('recursive' => true, 'like' => false);
                    $variables = $variableClass->searchInstances($variablePropertyFilters, $variableOptions);
                    
                    // If we can infer the related test...
                    if (isset($newItemResultRelatedTest) === true) {
                        
                        foreach ($variables as $variableUri => $variable) {
                            $newVariable = $this->createVariableObject($variable);
                            $outcomeRds->storeTestVariable($newResultIdentifier, $newItemResultRelatedTest, $newVariable, $newResultIdentifier);
                        }
                    }
                    
                } else {
                    $this->out("Skipping Delivery Result with URI '${deliveryResultUri}'. Malformed Delivery Result.");
                }
            }
            
            // Retrieve next batch of Delivery Results.
            $limit += $batch;
            $offset += $batch;
            $deliveryResults = $deliveryResultClass->getInstances(false, array('offset' => $offset, 'limit' => $limit));
        }
    }
    
    private function createVariableObject(\core_kernel_classes_Resource $variable)
    {
        $newVariable = false;
        $variableUri = $variable->getUri();
        
        $variableProperties = array(
                        self::VARIABLEIDENTIFIERPROPERTYURI,
                        self::VARIABLECARDINALITYPROPERTYURI,
                        self::VARIABLEBASETYPEPROPERTYURI,
                        self::VARIABLECORRECTRESPONSEPROPERTYURI,
                        self::VARIABLEVALUEPROPERTYURI,
                        self::VARIABLEPOCHPROPERTYURI,
                        self::VARIABLENORMALMAXIMUMPROPERTYURI,
                        self::VARIABLENORMALMINIMUMPROPERTYURI,
                        self::RDFTYPEPROPERTY
        );
        
        $variableValues = $variable->getPropertiesValues($variableProperties);
        
        $noIdentifier = empty($variableValues[self::VARIABLEIDENTIFIERPROPERTYURI]);
        $noCardinality = empty($variableValues[self::VARIABLECARDINALITYPROPERTYURI]) || current($variableValues[self::VARIABLECARDINALITYPROPERTYURI])->__toString() == '';
        $noBasetype = empty($variableValues[self::VARIABLEBASETYPEPROPERTYURI]) || current($variableValues[self::VARIABLEBASETYPEPROPERTYURI])->__toString() == '';
        $noCorrectResponse = empty($variableValues[self::VARIABLECORRECTRESPONSEPROPERTYURI]) || current($variableValues[self::VARIABLECORRECTRESPONSEPROPERTYURI])->__toString() == '';
        $noValue = empty($variableValues[self::VARIABLEVALUEPROPERTYURI]) || current($variableValues[self::VARIABLEVALUEPROPERTYURI])->__toString() == '';
        $noEpoch = empty($variableValues[self::VARIABLEPOCHPROPERTYURI]) || current($variableValues[self::VARIABLEPOCHPROPERTYURI])->__toString() == '';
        $noVariableType = empty($variableValues[self::RDFTYPEPROPERTY]) || !current($variableValues[self::RDFTYPEPROPERTY]) instanceof \core_kernel_classes_Resource;
        $noNormalMaximum = empty($variableValues[self::VARIABLENORMALMAXIMUMPROPERTYURI]) || current($variableValues[self::VARIABLENORMALMAXIMUMPROPERTYURI])->__toString() == '';
        $noNormalMinimum = empty($variableValues[self::VARIABLENORMALMINIMUMPROPERTYURI]) || current($variableValues[self::VARIABLENORMALMINIMUMPROPERTYURI])->__toString() == '';
        
        if ($noIdentifier === false && $noEpoch === false && $noVariableType === false) {
            $newVariableIdentifier = current($variableValues[self::VARIABLEIDENTIFIERPROPERTYURI]);
            $newVariableType = current($variableValues[self::RDFTYPEPROPERTY])->getUri();
            $newVariableEpoch = current($variableValues[self::VARIABLEPOCHPROPERTYURI])->__toString();
            $newVariableCardinality = ($noCardinality === true) ? null : current($variableValues[self::VARIABLECARDINALITYPROPERTYURI])->__toString();
            $newVariableBasetype = ($noBasetype === true) ? null : current($variableValues[self::VARIABLEBASETYPEPROPERTYURI])->__toString();
            $newVariableCorrectResponse = ($noCorrectResponse === true) ? null : (current($variableValues[self::VARIABLECORRECTRESPONSEPROPERTYURI])->getUri() === self::GENERISTRUEINSTANCEURI) ? true : false;
            $newVariableValue = ($noValue === true) ? null : base64_decode(current($variableValues[self::VARIABLEVALUEPROPERTYURI])->__toString());
            $newNormalMaximum = ($noNormalMaximum === true) ? null : floatval(current($variableValues[self::VARIABLENORMALMAXIMUMPROPERTYURI])->__toString());
            $newNormalMinimum = ($noNormalMinimum === true) ? null : floatval(current($variableValues[self::VARIABLENORMALMINIMUMPROPERTYURI])->__toString());
        
            if ($newVariableType === self::RESPONSEVARIABLECLASSURI || $newVariableType === self::OUTCOMEVARIABLECLASSURI) {
        
                // Let's infer whether it's a Response or Outcome Variable.
                $newVariable = ($newVariableType === self::RESPONSEVARIABLECLASSURI) ? new \taoResultServer_models_classes_ResponseVariable() : new \taoResultServer_models_classes_OutcomeVariable();
                $newVariable->setIdentifier($newVariableIdentifier);
                $newVariable->setEpoch($newVariableEpoch);
        
                if ($newVariableCardinality !== null) {
                    $newVariable->setCardinality($newVariableCardinality);
                }
        
                if ($newVariableBasetype !== null) {
                    $newVariable->setBaseType($newVariableBasetype);
                }
        
                if ($newVariableCorrectResponse !== null && $newVariable instanceof \taoResultServer_models_classes_ResponseVariable) {
                    $newVariable->setCorrectResponse($newVariableCorrectResponse);
                }
        
                if ($newVariableValue !== null) {
                    if ($newVariable instanceof \taoResultServer_models_classes_ResponseVariable) {
                        $newVariable->setCandidateResponse($newVariableValue);
                    } else {
                        $newVariable->setValue($newVariableValue);
                    }
                }
        
                if ($newNormalMaximum !== null && $newVariable instanceof \taoResultServer_models_classes_OutcomeVariable) {
                    $newVariable->setNormalMaximum($newNormalMaximum);
                }
        
                if ($newNormalMinimum !== null && $newVariable instanceof \taoResultServer_models_classes_OutcomeVariable) {
                    $newVariable->setNormalMinimum($normalMinimum);
                }
            }
        }
        
        return $newVariable;
    }
    
    public function postRun()
    {
        
    }
}


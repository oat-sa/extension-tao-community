<div class="splash-screen-wrapper tao-scope">
    <div id="splash-screen" class="modal splash-modal">
        <div class="modal-title">
            <?=__('Get started with TAO')?>
        </div>
        <ul class="modal-nav plain clearfix">
            <li class="active"><a href="#" data-panel="overview"><?=__('Overview')?></a></li>
            <li><a href="#" data-panel="videos"><?=__('Videos')?></a></li>
        </ul>
        <div class="modal-content">
            <div class="panels" data-panel-id="overview" style="display: block;">
                <p><?=__('Discover how easy it is to create an assessment with TAO!')?></p>
                <? $extensions = get_data('defaultExtensions')?>
                <div class="diagram">
                    <div class="grid-row">
                        <div class="col-6">
                           <a href="#" 
                              class="block pentagon<? if(!$extensions['items']['enabled']): ?> disabled<? endif ?>" 
                              data-module-name="items"
                              data-url="<?=_url('index', null, null, array('structure' => $extensions['items']['id'], 'ext' => $extensions['items']['extension']))?>">
                               <span class="icon-item"></span>
                               <?=__($extensions['items']['name'])?>
                           </a>
                        </div>
                        <div class="col-6">
                           <a href="#" 
                              class="block pentagon <? if(!$extensions['subjects']['enabled']): ?> disabled<? endif ?>" 
                              data-module-name="subjects"
                              data-url="<?=_url('index', null, null, array('structure' => $extensions['subjects']['id'], 'ext' => $extensions['subjects']['extension']))?>">
                               <span class="icon-test-taker"></span>
                               <?=__($extensions['subjects']['name'])?>
                           </a>
                        </div>
                    </div>
                    <div class="grid-row">
                        <div class="col-6">
                           <a href="#" 
                              class="block pentagon<? if(!$extensions['tests']['enabled']): ?> disabled<? endif ?>" 
                              data-module-name="tests"
                              data-url="<?=_url('index', null, null, array('structure' => $extensions['tests']['id'], 'ext' => $extensions['tests']['extension']))?>">
                               <span class="icon-test"></span>
                               <?=__($extensions['tests']['name'])?>
                           </a>
                        </div>
                        <div class="col-6">
                           <a href="#" 
                              class="block pentagon<? if(!$extensions['groups']['enabled']): ?> disabled<? endif ?>" 
                              data-module-name="groups"
                              data-url="<?=_url('index', null, null, array('structure' => $extensions['groups']['id'], 'ext' => $extensions['groups']['extension']))?>">
                               <span class="icon-test-takers"></span>
                               <?=__($extensions['groups']['name'])?>
                           </a>
                        </div>
                    </div>
                    <div class="grid-row">
                        <div class="col-12">
                           <a href="#" 
                              class="block pentagon wide<? if(!$extensions['delivery']['enabled']): ?> disabled<? endif ?>" 
                              data-module-name="delivery"
                              data-url="<?=_url('index', null, null, array('structure' => $extensions['delivery']['id'], 'ext' => $extensions['delivery']['extension']))?>">
                               <span class="icon-delivery"></span>
                               <?=__($extensions['delivery']['name'])?>
                           </a>
                        </div>
                    </div>
                    <div class="grid-row">
                        <div class="col-12">
                           <a href="#" 
                              class="block wide<? if(!$extensions['results']['enabled']): ?> disabled<? endif ?>"  
                              data-module-name="results"
                              data-url="<?=_url('index', null, null, array('structure' => $extensions['results']['id'], 'ext' => $extensions['results']['extension']))?>">
                               <span class="icon-result"></span>
                               <?=__($extensions['results']['name'])?>
                           </a>
                        </div>
                    </div>
                </div>
                <div class="desc">
                    <div class="module-desc default">
                        <span><?=__('Select an icon on the left to learn more about this step.')?><span/>
                    </div>
                    <?foreach(get_data('extensions') as $extension): ?>
                        <div class="module-desc" data-module="<?=$extension['id']?>">
                            <span class="icon"></span>
                            <? include 'splash/' . $extension['name'] . '.tpl' ?> 
                        </div>
                    <?endforeach?>
                </div>
                <?
                    $moreShowed = false;
                    foreach(get_data('additionalExtensions') as $extension):
                ?>
                <?if(!$moreShowed) echo '<span class="more">More:</span>';?>
                    <a href="#" class="module new-module" data-module-name="<?=$extension['id']?>" data-url="<?=_url('index', null, null, array('structure' => $extension['id'], 'ext' => $extension['extension']))?>">
                        <span class="icon-extension"></span>
                        <?=__($extension['name'])?>
                    </a>
                <?      $moreShowed = true;
                    endforeach?>
            </div>
            <div class="panels" data-panel-id="videos">
            </div>
        </div>
        <div class="modal-footer clearfix">
            <div class="checkbox-wrapper">
                <label class="checkbox">
                    <input id="nosplash" type="checkbox" />
                    <span class="icon-checkbox"></span>
                    <?=__('Do not show this window when TAO opens.')?>
                </label>
                <span class="note"><?=__('Note: You can access this overview whenever you need via the Help icon.')?></span>
            </div>
            <button id="splash-close-btn" class="btn-info" type="button" disabled="disabled"><span class="icon-save"></span><?=__('Close & Start using TAO!')?></button>
        </div>
    </div>
</div>

<div id="container" <? if ($editMode): ?>class="editmode"<? endif ?>>

 <!-- Entire page wrap -->
   <div id="tabs" class="tabs"> <!-- All the header tabs -->
   <ul>
<? foreach ($structure as $section): ?>
    <li><a href="#tabs-<?=$section['name']?>"><?=$section['title']?></a></li>
<? endforeach ?>
   </ul>
<? foreach ($structure as $section): ?>
     <div id="tabs-<?=$section['name']?>" class="wrapper"> <!-- Body of the tab section -->
	     <div class="column_one"> <!-- First column; might be only column -->
		   <div class="column_text">
           <? foreach ($section['texts'] as $prop): ?>
            <div> <!-- Text section for the selected tab -->
    		    <h3><?=$prop['title']?></h3>  <!-- Text section title -->
                <p><?=$this->decorate($prop, 'text')?></p> <!-- Actual text content -->
		    </div> <!-- Ends a single text section -->
           <? endforeach ?>
		    </div> <!-- End column_text -->
         </div> <!-- End of column one-->
		 <div class="column_two"> <!-- second column, if needed -->
		   <div class="column_text">
		    <div class="headshot"> <!-- Picture image.  needs some if/then logic for default. -->
			   <img src="/assets/images/image_default.png" />
			</div>
		    <? foreach ($section['properties'] as $prop): ?>
               <div class="field_title <?=$prop['name']?>"><?=$prop['title']?></div>
			   <div class="field_content"><?=$this->decorate($prop, 'property')?></div>
            <? endforeach ?>
			</div> <!-- End column_text -->
         </div> <!-- End second column -->

    </div> <!-- End individual tab div / wrapper -->
<? endforeach ?>
   </div> <!-- End 'tabs' div -->
<? if ($editMode): ?>
<div id="editbuttons">
    <button id="edit"></button>
</div>
<?=$this->renderTemplate('editorDialogs', ['name' => $this->getRequestedName()])?>
<? endif ?>
</div> <!-- End Container div -->

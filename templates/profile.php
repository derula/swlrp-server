<div id="container"> <!-- Entire page wrap -->
   <div id="tab_row"> <!-- All the header tabs -->
<? foreach ($structure as $section): ?>
      <div class="tab" id="<?=$section['title']?>"> <!-- Individual section tab -->
	      <h1 class="tab inactive"><?=$section['title']?></h1> <!-- Tab text -->
	  </div>
<? endforeach ?>
   </div> <!-- End tab row -->
<? foreach ($structure as $section): ?>
     <div class="wrapper"> <!-- Body of the tab section -->
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
			   <img src="/assets/images/image_default.gif" />
			</div>
		    <? foreach ($section['properties'] as $prop): ?>
               <div class="field_title <?=$prop['name']?>"><?=$prop['title']?></div>
			   <div class="field_content"><?=$this->decorate($prop, 'property')?></div>
            <? endforeach ?>
			</div> <!-- End column_text -->
         </div> <!-- End second column -->

    </div> <!-- End Wrapper div -->
<? endforeach ?>
<? if ($editMode): ?>
<footer>
    <hr />
    <button id="edit"></button>
</footer>
<?=$this->renderTemplate('editorDialogs', ['name' => $this->getRequestedName()])?>
<? endif ?>
</div> <!-- End Container div -->

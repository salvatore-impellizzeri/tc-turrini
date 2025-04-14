<div class="photo-editor photo-editor--background" data-toggle="closed">
  <div class="photo-editor__modal">
    <div class="photo-editor__modal--inner">
      <div class="left">
        <div class="desktop-frame" data-toggle="true">
          <img class="desktop-frame__img" />
        </div>
        <?php if(!empty($mobile)): ?>
        <div class="mobile-frame" data-toggle="false">
          <img class="mobile-frame__img" />
        </div>
        <?php endif; ?>
      </div>
      <div class="right">
        <button type="button" id="close-editor"><span class="material-symbols-rounded">close</span></button>
        <div class="photo-editor-controls">
          <?php if(!empty($mobile)): ?>
            <div class="photo-editor-controls__device">
              <div class="device" id="desktop">
                <?php echo $this->Backend->materialIcon('desktop_mac'); ?>
                <span>Desktop</span>
              </div>
              <div class="device" id="mobile">
                <?php echo $this->Backend->materialIcon('smartphone'); ?>
                <span>Mobile</span>
              </div>
              <div class="device device--selector"></div>
            </div>
          <?php endif; ?>
          <div class="photo-editor-controls__image-info">
            <div class="attributes">
              <div class="attributes__heading">
                Attributi immagine
              </div>
              <div class="attributes__sizes">

                <div class="attributes__sizes--desktop attributes__size active" data-device="desktop">
                  <div class="attributes__sizes-width">
                    <span>Larghezza:</span>
                    <span class="attributes__sizes-width-value">
                      <?php echo $width; ?>
                    </span>
                  </div>
                  <div class="attributes__sizes-height">
                    <span>Altezza:</span>
                    <span class="attributes__sizes-height-value">
                      <?php echo $height; ?>
                    </span>
                  </div>
                </div>
                <div class="attributes__sizes--mobile attributes__size" data-device="mobile">
                  <div class="attributes__sizes-width">
                      <span>Larghezza:</span>
                      <span class="attributes__sizes-width-value">
                        <?php echo $mobileWidth; ?>
                      </span>
                    </div>
                    <div class="attributes__sizes-height">
                      <span>Altezza:</span>
                      <span class="attributes__sizes-height-value">
                        <?php echo $mobileHeight; ?>
                      </span>
                    </div>
                  </div>
                </div>

              <div class="attributes__list">
                <div class="input input--bottom-rounded labelled text required">
                  <label>Nome file</label>
                  <input name="_filename" maxlength="255" type="text" id="" value="" placeholder="">
                </div>
                <?php /*
                <div class="input input--bottom-rounded labelled text required">
                  <label>Caption</label>
                  <input name="_caption" maxlength="255" type="text" id="" value="" placeholder="">
                </div>
                */ ?>
                <div class="input  input--bottom-rounded labelled text required">
                  <label>Descrizione immagine (alt)</label>
                  <input name="_title" maxlength="255" type="text" id="" value="" placeholder="">
                </div>

                <?php if (!empty($video)): ?>
                  <div class="input  input--bottom-rounded labelled text required">
                    <label>Video collegato</label>
                    <input name="_video" maxlength="255" type="text" id="" value="" placeholder="">
                  </div>
                <?php else: ?>
                  <input name="_video" maxlength="255" type="hidden" id="" value="" placeholder="">
                <?php endif; ?>


                <?php if (!empty($videoMobile)): ?>
                  <div class="input  input--bottom-rounded labelled text required">
                    <label>Video collegato (mobile)</label>
                    <input name="_video_mobile" maxlength="255" type="text" id="" value="" placeholder="">
                  </div>
                <?php else: ?>
                  <input name="_video_mobile" maxlength="255" type="hidden" id="" value="" placeholder="">
                <?php endif; ?>
              </div>
            </div>
              <?php if(!empty($mobile)): ?>
              <div class="alt-image uploader" data-toggle="false">
                <div class="upload">
                  <div class="alt-image__upload-area upload__area"></div>
                  <div class="upload__text">
                    <div class="upload__text--inner-label">Carica un'immagine diversa su mobile</div>
                    <span class="material-symbols-rounded">file_upload</span>
                    <div>Clicca per caricare l’immagine o trascinala nel box</div>
                  </div>
                </div>
              </div>
              <?php endif; ?>

          </div>
          <div class="photo-editor__modal--submit">
            <span class="btn btn--light undo-photo-editor">Annulla</span>
            <span class="btn btn--primary btn--icon save-photo-editor">
              Salva <div class="material-symbols-rounded">done_all</div>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
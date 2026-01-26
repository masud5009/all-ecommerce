 <div class="modal fade" id="editMenuModal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header bg-secondary text-white">
                 <h5 class="modal-title">Edit Menu Item</h5>
                 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                     aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <input type="hidden" id="editItemId" />
                 <div class="form-group">
                     <input type="text" id="editTitle" placeholder="Title" class="form-control" />
                     <p class="text-danger d-none m-0" id="eerr_editTitle"> </p>
                 </div>
                 <div class="form-group">
                     <input type="text" id="editUrl" placeholder="URL" class="form-control" />
                     <p class="text-danger d-none m-0" id="eerr_editUrl"> </p>
                 </div>

                 <div class="form-group">
                     <select id="editTarget" class="form-select">
                         <option value="_self">Same Tab</option>
                         <option value="_blank">New Tab</option>
                     </select>
                 </div>
             </div>
             <div class="modal-footer">
                 <button class="btn btn-secondary" id="updateMenuItem">
                     <i class="fa fa-edit me-1"></i> Update
                 </button>
             </div>
         </div>
     </div>
 </div>

<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container">

<h1 class="mb-4 mt-4">Select2 Modify No Result </h1>
<form>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputName"><strong>Select2 Default</strong></label>
        <select class="form-control" name="name" id="selectName">
          <option value=""></option>
          <?php foreach ($supplier as $key => $value) : ?>
            <option value="<?= $value->id ?>"><?= $value->first_name ?></option>
          <?php endforeach ?>
        </select>
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail"><strong>Select2 With Ajax & Infinity Scroll</strong></label>
      <select class="form-control" name="name" id="selectNameAjax">
      
      </select>
    </div>
  </div>
</form>
</div>

<div id="modal_add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLiveLabel">Modal Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
    <form action="#" method="post" id="form_quick_add_supplier">
            <input type="hidden" name="elemen" id="idElemen">
          <div class="form-group">
            <label for="md_inputName">Name</label>
            <input required type="text" name="name" class="form-control" id="md_inputName">
          </div>
          <div class="form-group">
            <label for="md_inputEmail">Email</label>
            <input type="text" class="form-control" name="email" id="md_inputEmail">
          </div>
          <div class="form-group">
            <label for="md_inputPhone">Phone</label>
            <input type="text" name="phone" class="form-control" id="md_inputPhone">
          </div>
          <div class="form-group">
            <label for="md_inputCompany">Company</label>
            <input type="text" name="company" class="form-control" id="md_inputCompany">
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="/select2/js/select2.full.min.js"></script>
<script>
    $(document).ready(function(){

      // Select2 Modify No Result
      // Select2 Default
        $('#selectName').select2({
            placeholder: 'Search name',
            language: {
            noResults: function() {
                var name = $('#selectName')
                    .data('select2')
                    .dropdown.$search.val();
                if(!name==""){
                  return (
                        '<button type="button" data-id_elemen="#selectName" data-name="' +
                        name +
                          '" class="btn btn-link quick_add_supplier"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>Add '+name+'</button>'
                    );
                    }
                },
            },
            escapeMarkup: function(markup) {
                return markup;
            }
        });

        $(document).on('click','.quick_add_supplier',function(){
          const text = $(this).data('name');
          const id_el = $(this).data('id_elemen');
          $(id_el).select2('close');
          $('#modal_add').find('#md_inputName').val(text);
          $('#modal_add').find('#idElemen').val(id_el);
          $('#modal_add').modal('show')
        })

        $('#form_quick_add_supplier').submit(function(e){
          e.preventDefault();
          
          const data = $(this).serialize();

          $.ajax({
            type: "POST",
            url: "/home/quick_save",
            data: data,
            dataType: "json",
            success: function(result) {
              if(result.status){
                  const newOption = new Option(result.data.name,result.data.id,true,true);
                  $(result.elemen).append(newOption).trigger('change');
                  $('#modal_add').modal('hide');
              }else{
                alert("something wrong")
              }
            }
          })
        });

        $('#modal_add').on('hidden.bs.modal', function () {
          $('#form_quick_add_supplier')[0].reset();
        })


      // Select2 Modify No Result
      // Select2 with Ajax & Infinty Scroll
        $('#selectNameAjax').select2({
            ajax: {
                url: "/home/json",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page||1
                    }

                },
                processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used

                params.page = params.page || 1;

                return {
                        results: data.results,
                        pagination: {
                                more: (params.page * 10) < data.count_filtered
                        }
                    };
                },
                // cache: true
            },
            placeholder: 'Search name',
            language: {
            noResults: function() {
                var name = $('#selectNameAjax')
                    .data('select2')
                    .dropdown.$search.val();
                if(!name==""){
                  return (
                        '<button type="button" data-id_elemen="#selectNameAjax" data-name="' +
                        name +
                          '" class="btn btn-link quick_add_supplier"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>Add '+name+'</button>'
                    );
                    }
                },
            },
            escapeMarkup: function(markup) {
                return markup;
            }
        });
    });
</script>
<?= $this->endSection() ?>
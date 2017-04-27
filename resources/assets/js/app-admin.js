$('#addRoleSubmitter').click(function(){
    form = $('#addRoleForm');
    data = JSON.stringify(form.serializeArray());
    axios.post(form.attr('action'), {
        name: form.find('input[name=name]').val(),
        description: form.find('input[name=description]').val(),
        discord_id: form.find('input[name=discord_id]').val(),
        icon: form.find('input[name=icon]').val(),
        slug: form.find('input[name=slug]').val(),
        _token: form.find('input[name=_token]').val()
    })
    .then(function (response) {
        $.notify("role added", 'success');
        console.log(response);
    })
    .catch(function (error) {
        $.notify("error", 'error');
        console.log(error);
    });
    form.find("input").val("");
})

$('.restrictRoleButton').click(function(){
     axios.get('/permissions').then(function(response){
        options = [];
        Object.keys(response.data).forEach(function(key) {
            options.push({text: response.data[key].name, value: response.data[key].id});
        });
        bootbox.prompt({
            title: "Choose Restrictions",
            inputType: 'checkbox',
            inputOptions: options,
            callback: function (result) {
                console.log(result); 
                console.log(this);
                var role_id = $(this).attr('data-id');
                axios.post('/roles/restrict', {
                    role: role_id,
                    permissions: result
                }).then( function (response){
                    $.notify("restricted", 'success');
                }).catch(function (error){
                    $.notify("error", 'error');
                })
            }.bind(this)

        });
    }.bind(this))
})
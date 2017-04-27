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
        console.log(response);
    })
    .catch(function (error) {
        console.log(error);
    });
    form.find("input").val("");
})
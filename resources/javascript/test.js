// id of new roles being added
var boxId = 1;
// adds role when "Add role" is clicked
function addField(e)
{
    var tempId = boxId;
    // finds end of role list
    var end = document.getElementById("role_list");
    // textbox for new role name
    var inputRoleName = document.createElement("input");
    inputRoleName.name="role_new"+tempId;
    inputRoleName.id="role_new"+tempId;
    // button to remove new role
    var removeRoleButton = document.createElement("button");
    removeRoleButton.id = "role_new_button"+tempId;
    removeRoleButton.type = "button";
    removeRoleButton.innerHTML='Remove';
    end.appendChild(document.createElement("br"));
    end.appendChild(inputRoleName);
    end.appendChild(removeRoleButton);
    end.appendChild(document.createElement("br"));
    removeRoleButton.addEventListener("click", function() { document.getElementById("role_new"+tempId).remove(); document.getElementById("role_new_button"+tempId).remove();  });
    boxId++;
}
// Disables/enables existing role. Disabled roles will be removed on submit
function removeRole(e, id)
{
    var role_name = document.getElementById("role_name"+id);
    var role_button = document.getElementById("role_button"+id);
    var role_div = document.getElementById("role"+id);
    // re-enables role
    if (role_button.value == "remove")
    {
        role_name.disabled=false;
        role_name.style="";
        role_button.value="";
        role_button.innerHTML="Remove";
        ((document.getElementById("role_remove"+id))? document.getElementById("role_remove"+id).remove(): '');
    }
    // sets role to disabled for removal
    else
    {
        var removeRole = document.createElement("input");
        removeRole.type="hidden";
        removeRole.id="role_remove"+id;
        removeRole.name="role_remove"+id;
        role_div.appendChild(removeRole);
        role_name.disabled=true;
        role_name.style="background: #CCC;";
        role_button.value="remove";
        role_button.innerHTML="Add";
    }
}
// sets team to be deleted from database and submits form
function setDeletedTeam(e, id)
{
    document.getElementById('team').value=id;
    document.getElementById('myteams_form').submit();
}

// sets role user wants to apply for on jointeams page and submits form
function setAppliedRole(event, id)
{
    document.getElementById('role').value=id;
    document.getElementById('jointeams_form').submit();
}

function ajaxRemoveInterest(event, role_id)
{
    $.post("req_handler.php",
        {
            action: "remove",
            role_id: role_id
        },
        function(data, status) {
            window.location.reload();
        }
    );
}

function ajaxAddInterest(event)
{
    $tag = ((document.getElementById("new-interest").value)? document.getElementById("new-interest").value : null) // empty interest default. rejected
    console.log($tag);
    if ($tag != null) {
        $.post("req_handler.php",
            {
                action: "add",
                tag: $tag
            },
            function (data, status) {
                window.location.reload();
            }
        );
    }
}

function ajaxAddInterestOnEnter(event)
{
    if(event.keyCode == 13){
        ajaxAddInterest(event);
    }
}
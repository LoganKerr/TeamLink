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
    // re-enables role
    if (role_button.value == "remove")
    {
        role_name.disabled=false;
        role_name.style="";
        role_button.value="";
    }
    // sets role to disabled for removal
    else
    {
        var removeRole = document.createElement("input");
        removeRole.type="hidden";
        removeRole.name="role_remove"+id;
        role_button.appendChild(removeRole);
        role_name.disabled=true;
        role_name.style="background: #CCC;";
        role_button.value="remove";
    }
}
// sets team to be deleted from database
function setDeletedTeam(e, id)
{
    document.getElementById('team').value=id;
    document.getElementById('myteams_form').submit();
}

var ENTER_KEY = 13;
var ESC_KEY = 27;

var todoTemplate = $('#todo-template').html();
var todoList = $('.todo-list');
var todoCount = $('.todo-count strong');

$('#add-todo').on('submit', addNewTodo);
bindEvents();

function bindEvents() {
  $('.toggle').unbind();
  $('.destroy').unbind();
  $('.todo-list li label').unbind();
  $('.edit').unbind();
  $('.edit').unbind();

  $('.toggle').on('change', onTodoChecked);
  $('.destroy').on('click', onTodoDelete);
  $('.todo-list li label').on('dblclick', onTodoEnableEdit);
  $('.edit').on('blur', onTodoEdit);
  $('.edit').on('keydown', onTodoEditKeypress);
}

function updateTodoCount() {
  $(todoCount).html($(todoList).children().length - $('.completed').length);
}

function addNewTodo(e) {
  e.preventDefault();

  var todo = $('.new-todo').val();

  $.ajax({
    url: '/add-todo',
    method: 'post',
    dataType: 'json',
    data: { todo: todo },
    success: function(data) {
      if (data.success === true) {
        var html = _.template(todoTemplate, {
          id: data.id,
          todo: todo
        });

        $(todoList).append(html);
        bindEvents();
        updateTodoCount();
        $('.new-todo').val('');
      } else {
        alert('There was an error adding your todo.');
      }
    },
    error: function(e) {
      alert('There was an error adding your todo.');
    }
  });

  return false;
}

function onTodoChecked(e) {
  var li = $(this).parent().parent();
  var checked = $(this).is(':checked');

  $.ajax({
    url: '/update-todo',
    method: 'post',
    dataType: 'json',
    data: { action: 'completed', checked: checked, id: $(li).data('id') },
    success: function(data) {
      if (data.success === true) {
        if (checked) {
          $(li).addClass('completed');
        } else {
          $(li).removeClass('completed');
        }
        updateTodoCount();
      } else {
        alert(data.message);
      }
    },
    error: function(e) {
      alert('There was an error updating your todo.');
    }
  });
}

function onTodoDelete(e) {
  var li = $(this).parent().parent();

  $.ajax({
    url: '/update-todo',
    method: 'post',
    dataType: 'json',
    data: { action: 'delete', id: $(li).data('id') },
    success: function(data) {
      if (data.success === true) {
        $(li).remove();
        updateTodoCount();
      } else {
        alert(data.message);
      }
    },
    error: function(e) {
      alert('There was an error deleting your todo.');
    }
  });
}

function onTodoEnableEdit(e) {
  var parent = $(this).parent().parent();

  $(parent).addClass('editing');
  $(parent).find('input').focus();
}

function onTodoEdit(e) {
  var li = $(this).parent();
  var todo = $(this).val().trim();
  var label = $(li).find('label');

  $(li).removeClass('editing');
  if (todo == $(label).text()) {
    return;
  }

  $.ajax({
    url: '/update-todo',
    method: 'post',
    dataType: 'json',
    data: { action: 'edit', todo: todo, id: $(li).data('id') },
    success: function(data) {
      if (data.success === true) {
        $(label).html(todo);
      } else {
        alert(data.message);
      }
    },
    error: function(e) {
      alert('There was an error updating your todo.');
    }
  });
}

function onTodoEditKeypress(e) {
  if (e.which === ENTER_KEY) {
    onTodoEdit.call(this);
  }
  if (e.which === ESC_KEY) {
    var todo = $(this).parent().find('label').text();

    $(this).val(todo);
    $(this).parent().removeClass('editing');
  }
}
post-type-manager
=================

Plugin for Wordpress to manager custom post type and fields 

How to use this ? 
-----------------

First, you can choose to add fields to existing post type :

> $page = new Post_Type_Manager('page');

Or create your own :

> $game = new Post_Type_Manager('game');
> $game->generate_post_type();

After that, you can add field on this type

> $game->add_text_field('My AweSome LaBel', 'My Poor description');

Type of fields
--------------

add_text_field($label, $description);
add_textarea_field($label, $description);
add_checkbox_field($label, $description);
add_select_field($label, $description, $options);
add_radio_field($label, $description, $options);
add_post_list_field($label, $description, $type_post);
add_image_field($label, $description);

$options = array(
  array(  'label' => 'Wahou 1',
          'value' => '1'),
  array(  'label' => 'Wahou 2',
          'value' => '2'),
  array(  'label' => 'Wahou 3',
          'value' => '3')
);

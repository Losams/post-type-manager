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

> add_field( $type, $label, $desc, $params, $metabox_id)

exemple 

> $game->add_field('text', 'My AweSome LaBel', 'My Poor description', array('repeatable' => true));


Type of fields
--------------

> text, textarea, checkbox, select, radio, post-list, image, editor, address, image
>
> $params post-type : $params = array( 'post_type' => 'page'); // for exemple
>
> $params select, radio : saw below


The array $params for radio, select, $options is in this format :

> $options = array(
>
>  array(  'label' => 'Wahou 1',
>
> 'value' => '1'),
>
>  array(  'label' => 'Wahou 2',
>
> 'value' => '2'),
>
>  array(  'label' => 'Wahou 3',
>
> 'value' => '3')
>
> );

You can try $params = array('repeatable' => true) if you trust in me (don't...)

Remove / Add support / taxonomy
-------------------------------

> $game->remove_support('editor');

> $game->add_support('editor');

> $game->add_taxonomy('tax');

> $game->remove_taxonomy('tax');

Add MetaBox
-----------

Post type manager generate a default box if you don't want to specify. It title can be change with :

> $game->set_box_title($box_title);

For those who want add many box, you can add box like that : 

> $game->create_meta_box('boxbox', 'BoxBox Title');

And then, adding field like that : 

> $game->add_field('image', 'My strong face', 'description', null, 'boxbox');

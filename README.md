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
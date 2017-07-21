INSERT IGNORE INTO `user_perms` ( `name`, `group`, `role`, `about` ) VALUES
    ( 'create_post_canal',  'Post Canal', 'Management', 'Allow user to create new post canal' ),
    ( 'read_post_canal',    'Post Canal', 'Management', 'Allow user to view all post canals' ),
    ( 'remove_post_canal',  'Post Canal', 'Management', 'Allow user to remove exists post canal' ),
    ( 'update_post_canal',  'Post Canal', 'Management', 'Allow user to update exists post canal' );
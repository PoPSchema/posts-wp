<?php
namespace PoP\PostsWP\TypeDataLoaders\Overrides;

use PoP\Posts\TypeDataLoaders\PostTypeDataLoader;
use PoP\PostsWP\TypeResolverPickers\ContentEntityTypeResolverPickerInterface;
use PoP\PostsWP\TypeResolverPickers\ContentEntityUnionTypeHelpers;

/**
 * In the context of WordPress, "Content Entities" are all posts (eg: posts, pages, attachments, events, etc)
 * Hence, this class can simply inherit from the Post dataloader, and add the post-types for all required types
 */
class ContentEntityUnionTypeDataLoader extends PostTypeDataLoader
{
    public function getObjectQuery(array $ids): array
    {
        $query = parent::getObjectQuery($ids);

        // From all post types from the member typeResolvers
        $query['post-types'] = ContentEntityUnionTypeHelpers::getPostUnionTypeResolverTargetTypeResolverPostTypes();

        return $query;
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = parent::getDataFromIdsQuery($ids);

        // From all post types from the member typeResolvers
        $query['post-types'] = ContentEntityUnionTypeHelpers::getPostUnionTypeResolverTargetTypeResolverPostTypes();

        return $query;
    }

    public function getObjects(array $ids): array
    {
        $posts = parent::getObjects($ids);

        // After executing `get_posts` it returns a list of posts, without converting the object to its own post type
        // Cast the posts to their own classes (eg: event)
        $postUnionTypeResolver = ContentEntityUnionTypeHelpers::getPostUnionTypeResolver();
        $posts = array_map(
            function($post) use($postUnionTypeResolver) {
                $targetTypeResolverPicker = $postUnionTypeResolver->getTargetTypeResolverPicker($post);
                if (is_null($targetTypeResolverPicker)) {
                    return $post;
                }
                if ($targetTypeResolverPicker instanceof ContentEntityTypeResolverPickerInterface) {
                    // Cast object, eg: from post to event
                    return $targetTypeResolverPicker->maybeCast($post);
                }
                return $post;
            },
            $posts
        );
        return $posts;
    }
}

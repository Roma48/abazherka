<?php

class Images
{

    public static function getImage($mediaId)
    {
        $db = JFactory::getDbo();

// Create a new query object.
        $query = $db->getQuery(true);

// Select all records from the user profile table where key begins with "custom.".
// Order it by the ordering field.
        $query->select($db->quoteName(array('file_url')));
        $query->from($db->quoteName('#__virtuemart_medias'));
        $query->where($db->quoteName('virtuemart_media_id') . ' LIKE '. $mediaId);

        // $query->where($db->quoteName('virtuemart_custom_id') . ' LIKE '. 9 . 'AND' . 10);

// Reset the query using our newly populated query object.
        $db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();

        return $results;
    }


}

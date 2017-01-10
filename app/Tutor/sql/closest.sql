SELECT
    `u`.`id` as `user_id`,

    -- Distance
    DISTANCE(:latitude, :longitude, `a`.`latitude`, `a`.`longitude`) As `distance`

FROM `addresses` AS `a`

INNER JOIN `address_user` AS `au`
ON `a`.`id` = `au`.`address_id`

LEFT JOIN `users` AS `u`
ON `au`.`user_id` = `u`.`id`

INNER JOIN `role_user` AS `ru`
ON `ru`.`user_id` = `u`.`id`

LEFT JOIN `roles` AS `r`
ON `r`.`id` = `ru`.`role_id`

LEFT JOIN `user_profiles` AS `up`
ON `up`.`user_id` = `u`.`id`

LEFT JOIN `subject_user` AS `su`
ON `su`.`user_id` = `u`.`id`

-- User is a tutor
WHERE `r`.`name` = ':role'

-- Only default addresses. No billing
AND `au`.`name` = ':addressName'

-- Only who teach a given list of subjects
AND `su`.`subject_id` IN ( :subjectIds )

-- Within lat/lng rectangle
AND `a`.`latitude`  BETWEEN :minLatitude  AND :maxLatitude
AND `a`.`longitude` BETWEEN :minLongitude AND :maxLongitude

-- Within a lat/lng circle
AND DISTANCE(:latitude, :longitude, `a`.`latitude`, `a`.`longitude`) < :radius

-- Stop duplicates (teaching multiple subject ids)
GROUP BY `u`.`id`

-- Ye
ORDER BY `distance` DESC

-- Ye
LIMIT :limit;

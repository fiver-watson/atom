<?php

/*
 * This file is part of the Access to Memory (AtoM) software.
 *
 * Access to Memory (AtoM) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Access to Memory (AtoM) is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Access to Memory (AtoM).  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * Include sub-item level "part" in the levels of description taxonomy
 *
 * @package    AccesstoMemory
 * @subpackage migration
 */
class arMigration0098
{
  const
    VERSION = 98, // The new database version
    MIN_MILESTONE = 1; // The minimum milestone required

  /**
   * Upgrade
   *
   * @return bool True if the upgrade succeeded, False otherwise
   */
  public function up($configuration)
  {
<<<<<<< HEAD
    // Create root repository
    $object = new QubitRepository;
    $object->id = QubitRepository::ROOT_ID;
    $object->save();

    // Get maximun rgt value
    $order = $object->rgt;

    // Obtain all repositories except the root
    $sql = sprintf("SELECT t1.id
      FROM %s t1
      LEFT JOIN %s t2
      ON t1.id = t2.id
      WHERE class_name = ?
      AND t1.id != ?;", QubitActor::TABLE_NAME, QubitObject::TABLE_NAME);

    $rows = QubitPdo::fetchAll($sql, array('QubitRepository', QubitRepository::ROOT_ID));

    // Add parent to all the existing repositories and update rgt and lft values
    foreach ($rows as $repository)
    {
      $sql = sprintf("UPDATE %s t1
        LEFT JOIN %s t2
        ON t1.id = t2.id
        SET parent_id = ?, lft = ?, rgt = ?
        WHERE t1.id = ?
        AND t1.id != ?;", QubitActor::TABLE_NAME, QubitObject::TABLE_NAME);

      QubitPdo::modify($sql, array(QubitRepository::ROOT_ID,
        $order++, $order++, $repository->id, QubitRepository::ROOT_ID));
    }

    // Set the new max rgt value for the root repository
    $object->rgt = $order;
    $object->save();

    // Add menu nodes for repository permissions
    if (null !== $parentNode = QubitMenu::getByName('groups'))
    {
      $menu = new QubitMenu;
      $menu->parentId = $parentNode->id;
      $menu->name = 'groupRepositoryAcl';
      $menu->path = 'aclGroup/indexRepositoryAcl?id=%currentId%';
      $menu->sourceCulture = 'en';
      $menu->label = 'Archival institution permissions';
      $menu->save();
    }
    else
    {
      $this->logSection('upgrade-sql', 'The group permissions menu node for repository could not be added.', null, 'ERROR');
    }

    if (null !== $parentNode = QubitMenu::getByName('users'))
    {
      $menu = new QubitMenu;
      $menu->parentId = $parentNode->id;
      $menu->name = 'userRepositoryAcl';
      $menu->path = 'user/indexRepositoryAcl?slug=%currentSlug%';
      $menu->sourceCulture = 'en';
      $menu->label = 'Archival institution permissions';
      $menu->save();
    }
    else
    {
      $this->logSection('upgrade-sql', 'The user permissions menu node for repository could not be added.', null, 'ERROR');
    }

=======
    // Add new term 'dates of existence'
    $term = new QubitTerm;
    $term->id = QubitTerm::DATES_OF_EXISTENCE_ID;
    $term->parentId = QubitTerm::ROOT_ID;
    $term->taxonomyId = QubitTaxonomy::ROOT_ID;
    $term->name = 'Dates of existence';
    $term->culture = 'en';
    $term->save();

    // Get actors with dates of existence
    $sql = sprintf("SELECT t1.id, dates_of_existence, culture
      FROM %s t1
      INNER JOIN %s t2
      ON t1.id = t2.id
      WHERE dates_of_existence
      IS NOT NULL;", QubitActor::TABLE_NAME, QubitActorI18n::TABLE_NAME);

    foreach (QubitPdo::fetchAll($sql) as $item)
    {
      $datesOfExistence[$item->id][$item->culture] = $item->dates_of_existence;
    }

    // Create dates of existence events
    foreach ($datesOfExistence as $actorId => $dates)
    {
      $event = new QubitEvent;
      $event->actorId = $actorId;
      $event->typeId = QubitTerm::DATES_OF_EXISTENCE_ID;

      foreach ($dates as $culture => $content)
      {
        $event->setDate($content, array('culture' => $culture));
      }

      $event->save();
    }

    // Remove dates_of_existence column from database
    $sql = sprintf("ALTER TABLE %s
      DROP COLUMN dates_of_existence;", QubitActorI18n::TABLE_NAME);

    QubitPdo::modify($sql);

>>>>>>> Add migration script and install data.
    return true;
  }
}
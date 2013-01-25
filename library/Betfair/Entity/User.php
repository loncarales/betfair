<?php
namespace Betfair\Entity;
/**
 *
 * @Table(name="users")
 * @Entity
 */
class User
{
	/**
	 *
	 * @var integer $id
	 * @Column(name="id", type="integer",nullable=false)
	 * @Id
	 * @GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @Column(type="string",length=60,nullable=true)
	 * @var string
	 */
	private $firstname;

	/**
	 * @Column(type="string",length=60,nullable=true)
	 * @var string
	 */
	private $lastname;

	public function __get($property)
	{
		return $this->$property;
	}
	public function __set($property,$value)
	{
		$this->$property = $value;
	}
}
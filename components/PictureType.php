<?php
abstract class PictureType extends BasicEnum {
	const isnew = 1;
	const ishit = 2;
	const issellout = 3;
	const isvitrina = 4;
	const islastincome = 5;

}


abstract class BasicEnum {
	private static $constCache = NULL;

	private static function getConstants() {
		if (self::$constCache === NULL) {
			$reflect = new ReflectionClass(get_called_class());
			self::$constCache = $reflect->getConstants();
		}

		return self::$constCache;
	}

	public static function getEnum($x){
		$constants = self::getConstants();
		print_r($constants);
		foreach ( $constants as $name => $value )
		{
			if ( $value == $x )
			{
				$constName = $name;
				break;
			}
			return 'none';
		}

		return $constName;
	}

	public static function isValidName($name, $strict = false) {
		$constants = self::getConstants();

		if ($strict) {
			return array_key_exists($name, $constants);
		}

		$keys = array_map('strtolower', array_keys($constants));
		return in_array(strtolower($name), $keys);
	}

	public static function isValidValue($value) {
		$values = array_values(self::getConstants());
		return in_array($value, $values, $strict = true);
	}

	final public static function FromString( $string )
	{
		if ( strpos( $string, '::' ) < 1 )
		{
			throw new Exception( 'Enum::FromString( $string ) Input string is not in the expected format.' );
		}
		list( $class, $const ) = explode( '::', $string );

		if ( class_exists( $class, false ) )
		{
			$reflector = new ReflectionClass( $class );
			if ( $reflector->IsSubClassOf( 'Enum' ) )
			{
				if ( $reflector->hasConstant( $const ) )
				{
					return eval( sprintf( 'return %s;', $string ) );
				}
			}
		}
		throw new Excption( sprintf( '%s does not map to an Enum field', $string ) );
	}
}

?>
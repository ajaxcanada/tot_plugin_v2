/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//Jquery

// Define a null value.
var foo = null;
// Two ways to achieve an undefined value.
var bar1 = undefined;
var bar2;

// Using an empty object literal
var person1 = {};
// Assign properties using "dot notation"
person1.firstName = "John";
person1.lastName = "Doe";
// Access properties using "dot notation"
alert( person1.firstName + " " + person1.lastName );
// Creating an object with the object literal syntax:
var person2 = {
firstName: "Jane",
lastName: "Doe"
};
alert( person2.firstName + " " + person2.lastName );
var people = {};
// Assign properties using "bracket notation"
// As mentioned, objects can also have objects as a property value
people[ "person1" ] = person1;
people[ "person2" ] = person2;
// Access properties using a mix of both bracket and dot notation
alert( people[ "person1" ].firstName );
alert( people[ "person2" ].firstName );



//  ARRAY
//  The array literal returns a foo.length value of 1:
var foo = [ 100 ];
alert( foo[ 0 ] ); // 100
alert( foo.length ); // 1

// The array constructor returns a bar.length value of 100:
var bar = new Array( 100 );
alert( bar[ 0 ] ); // undefined
alert( bar.length ); // 100

 
// Using the push(), pop(), unshift() and shift() methods on an array.
var foo = [];
foo.push( "a" );
foo.push( "b" );
alert( foo[ 0 ] ); // a
alert( foo[ 1 ] ); // b
alert( foo.length ); // 2
foo.pop();
alert( foo[ 0 ] ); // a
alert( foo[ 1 ] ); // undefined
alert( foo.length ); // 1
foo.unshift( "z" );
alert( foo[ 0 ] ); // z
alert( foo[ 1 ] ); // a
alert( foo.length ); // 2
foo.shift();
alert( foo[ 0 ] ); // a
alert( foo[ 1 ] ); // undefined
alert( foo.length ); // 1


// Checking the type of an arbitrary value.
var myValue = [ 1, 2, 3 ];
// Using JavaScript's typeof operator to test for primitive types:
typeof myValue === "string"; // false
typeof myValue === "number"; // false
typeof myValue === "undefined"; // false
typeof myValue === "boolean"; // false
// Using strict equality operator to check for null:
myValue === null; // false
// Using jQuery's methods to check for non-primitive types:
jQuery.isFunction( myValue ); // false
jQuery.isPlainObject( myValue ); // false
jQuery.isArray( myValue ); // true

// Addition vs. Concatenation
var foo = 1;
var bar = "2";
console.log( foo + bar ); // 12

// Coercing a string to act as a number.
var foo = 1;
var bar = "2";
console.log( foo + Number(bar) ); // 3


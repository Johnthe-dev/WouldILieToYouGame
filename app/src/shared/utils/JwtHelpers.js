import {useState, useEffect} from "react";

/*
* Hooks to grab the JWT Token and decode its data for logged in users.
*/

export const UseJwt = () => {
    const [jwt, setJwt] = useState(null);

    useEffect(() => {
        setJwt(window.localStorage.getItem("jwt-token"));
    }, [jwt]);

    return jwt;
};
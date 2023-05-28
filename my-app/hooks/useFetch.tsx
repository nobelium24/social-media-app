import React from "react"
import {useEffect, useState} from "react";
import axios from "axios";
import Cookies from "js-cookie";

const useFetch = (url: string, token:string | undefined) => {
    const [data1, setData1] = useState<any>(null);
    const [loading1, setLoading1] = useState<boolean>(true);
    const [error1, setError1] = useState<any>(null);
    // console.log(token, 33)
    useEffect(() => {
        setTimeout(()=>{
            axios.get(url, {
                headers:{
                    "Authorization":token,
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                }
            }).then((res)=>{
                setData1(res.data);
                setLoading1(false);
                Cookies.set("user", JSON.stringify(res.data))
            }).catch((err)=>{
                setError1(err.response.data);
                setLoading1(false);
            })
        }, 1000)
    });
    return {data1, error1, loading1}
}

export default useFetch
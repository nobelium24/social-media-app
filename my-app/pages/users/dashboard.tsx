import React from 'react'
import useFetch from '../../hooks/useFetch'
import Cookies from 'js-cookie'
import router, { useRouter } from 'next/router';
import NavBar from '../../components/NavBar';

const Dashboard:React.FC = () => {
    const userToken = Cookies.get("token")
    // console.log(userToken);
    const {data1, error1, loading1} = useFetch('http://localhost/socialMedia/socialMediaApp/verifyToken.php', userToken)
    // console.log(data1)
    const userData = JSON.parse(Cookies.get("user") || "{}")
    // console.log(userData);
    if(userData === undefined){
        router.push("/users/login")
    }
    if (userData.status === false){
        router.push("/users/login")
    }
    return (
        <>
            <div className='container-fluid mx-auto bg-dark' id='div1'>
                <NavBar />
                <div className='row shadow-lg p-3 d-flex align-items-center justify-content-center' id='div2'>
                    <div>

                    </div>
                </div>
            </div>
        </>
    )
}

export default Dashboard